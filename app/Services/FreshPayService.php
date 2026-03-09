<?php

namespace App\Services;

use App\Traits\GetGlobalInformationTrait;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class FreshPayService
{
    use GetGlobalInformationTrait;

    public const ALLOWED_OPERATORS = ['airtel', 'orange', 'mpesa', 'africell'];

    public function isValidOperator(string $operator): bool
    {
        return in_array(strtolower($operator), self::ALLOWED_OPERATORS, true);
    }

    public function generateReference(string $prefix = 'LP'): string
    {
        return sprintf('%s-%s', $prefix, Str::upper(Str::random(14)));
    }

    public function debit(array $input): array
    {
        return $this->request('debit', $input);
    }

    public function credit(array $input): array
    {
        return $this->request('credit', $input);
    }

    private function request(string $action, array $input): array
    {
        $settings = $this->get_basic_payment_info();

        $endpoint = rtrim($settings->freshpay_api_url ?? config('services.freshpay.url', ''), '/');
        $merchantId = $settings->freshpay_merchant_id ?? config('services.freshpay.merchant_id', '');
        $merchantSecret = $settings->freshpay_merchant_secret ?? config('services.freshpay.merchant_secret', '');

        $payload = [
            'merchant_id' => $merchantId,
            'merchant_secrete' => $merchantSecret,
            'amount' => (string) $input['amount'],
            'currency' => $input['currency'] ?? 'USD',
            'action' => $action,
            'customer_number' => $input['customer_number'],
            'firstname' => $input['firstname'] ?? 'TARMAC',
            'lastname' => $input['lastname'] ?? 'TARMAC',
            'email' => $input['email'] ?? 'kasisrael@gmail.com',
            'reference' => $input['reference'],
            'method' => strtolower($input['method']),
            'callback_url' => $input['callback_url'],
        ];

        if ($endpoint === '' || $merchantId === '' || $merchantSecret === '') {
            return [
                'ok' => false,
                'message' => __('FreshPay credentials are missing.'),
                'payload' => $payload,
                'response' => null,
            ];
        }

        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->timeout(20)->post($endpoint, $payload);

            $data = $response->json();
            $ok = $response->successful() && $this->isAcceptedResponse($data);

            return [
                'ok' => $ok,
                'message' => data_get($data, 'message', $ok ? __('FreshPay request sent.') : __('FreshPay request failed.')),
                'payload' => $payload,
                'response' => $data,
                'http_status' => $response->status(),
            ];
        } catch (\Throwable $th) {
            info('FreshPay request error: '.$th->getMessage());

            return [
                'ok' => false,
                'message' => __('Unable to connect to FreshPay API.'),
                'payload' => $payload,
                'response' => null,
            ];
        }
    }

    private function isAcceptedResponse(mixed $data): bool
    {
        $status = strtolower((string) data_get($data, 'status', ''));
        $state = strtolower((string) data_get($data, 'data.status', ''));

        return in_array($status, ['success', 'ok', 'accepted', 'pending'], true)
            || in_array($state, ['success', 'ok', 'accepted', 'pending'], true)
            || data_get($data, 'success') === true;
    }
}
