<?php

namespace App\Services;

use App\Traits\GetGlobalInformationTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
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

    public function generateRandomKey(int $length = 32): string
    {
        return Str::random($length);
    }

    public function debit(array $input): array
    {
        return $this->request('debit', $input);
    }

    public function credit(array $input): array
    {
        return $this->request('credit', $input);
    }

    public function processCallback(Request $request): array
    {
        $ip = $request->ip();
        if (! $this->isWhitelistedIp($ip)) {
            return [
                'ok' => false,
                'http_status' => 403,
                'message' => __('Unauthorized callback IP.'),
                'ip' => $ip,
            ];
        }

        $data = $this->extractCallbackData($request);
        if (! is_array($data)) {
            return $data;
        }

        $status = $this->extractStatus($data);
        $reference = $this->extractReference($data);
        $message = $this->extractMessage($data) ?: __('Callback processed');

        return [
            'ok' => true,
            'http_status' => 200,
            'message' => $message,
            'data' => $data,
            'reference' => $reference,
            'status' => $status,
            'is_success' => in_array($status, ['success', 'paid', 'completed'], true),
        ];
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
            'firstname' => $settings->freshpay_firstname ?? 'Fresh',
            'lastname' => $settings->freshpay_lastname ?? 'Pay',
            'email' => $settings->freshpay_email ?? 'support@example.com',
            'reference' => $input['reference'],
            'method' => strtolower($input['method']),
            'callback_url' => $input['callback_url'],
        ];

        $payload['e-mail'] = $payload['email'];

        if (! empty($settings->freshpay_callback_aes_key)) {
            $payload['encryption_key'] = $settings->freshpay_callback_aes_key;
        }

        if (! empty($settings->freshpay_callback_aes_iv)) {
            $payload['encryption_iv'] = $settings->freshpay_callback_aes_iv;
        }

        if ($endpoint === '' || $merchantId === '' || $merchantSecret === '') {
            Log::error('FreshPay missing credentials', [
                'endpoint' => $endpoint,
                'payload' => $this->maskSensitivePayload($payload),
            ]);

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

            if (! $ok) {
                Log::warning('FreshPay request failed', [
                    'http_status' => $response->status(),
                    'payload' => $this->maskSensitivePayload($payload),
                    'response' => $data,
                ]);
            } else {
                Log::info('FreshPay request accepted', [
                    'http_status' => $response->status(),
                    'payload' => $this->maskSensitivePayload($payload),
                    'response' => $data,
                ]);
            }

            return [
                'ok' => $ok,
                'message' => data_get($data, 'message')
                    ?? data_get($data, 'Message')
                    ?? data_get($data, 'comment')
                    ?? data_get($data, 'Comment')
                    ?? ($ok ? __('FreshPay request sent.') : __('FreshPay request failed.')),
                'payload' => $payload,
                'response' => $data,
                'http_status' => $response->status(),
            ];
        } catch (\Throwable $th) {
            Log::error('FreshPay request exception', [
                'error' => $th->getMessage(),
                'payload' => $this->maskSensitivePayload($payload),
            ]);

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
        $status = $this->extractStatus($data);
        $state = strtolower((string) (
            data_get($data, 'data.status')
            ?? data_get($data, 'data.Status')
            ?? data_get($data, 'data.transaction_status')
            ?? ''
        ));

        return in_array($status, ['success', 'ok', 'accepted', 'pending'], true)
            || in_array($state, ['success', 'ok', 'accepted', 'pending'], true)
            || data_get($data, 'success') === true;
    }

    private function extractStatus(array $data): string
    {
        return strtolower((string) (
            data_get($data, 'status')
            ?? data_get($data, 'Status')
            ?? data_get($data, 'transaction_status')
            ?? data_get($data, 'transactionStatus')
            ?? data_get($data, 'transaction.status')
            ?? data_get($data, 'transaction.Status')
            ?? data_get($data, 'state')
            ?? data_get($data, 'State')
            ?? data_get($data, 'result')
            ?? data_get($data, 'Result')
            ?? 'pending'
        ));
    }

    private function extractReference(array $data): string
    {
        return (string) (
            data_get($data, 'reference')
            ?? data_get($data, 'Reference')
            ?? data_get($data, 'transaction.reference')
            ?? data_get($data, 'transaction.Reference')
            ?? data_get($data, 'external_reference')
            ?? ''
        );
    }

    private function extractMessage(array $data): string
    {
        return (string) (
            data_get($data, 'message')
            ?? data_get($data, 'Message')
            ?? data_get($data, 'comment')
            ?? data_get($data, 'Comment')
            ?? data_get($data, 'reason')
            ?? data_get($data, 'Reason')
            ?? data_get($data, 'description')
            ?? data_get($data, 'Description')
            ?? ''
        );
    }

    private function extractCallbackData(Request $request): array
    {
        $plainData = $request->all();
        if (is_array($plainData) && (
            array_key_exists('status', $plainData)
            || array_key_exists('Status', $plainData)
            || array_key_exists('reference', $plainData)
            || array_key_exists('Reference', $plainData)
        )) {
            return $plainData;
        }

        $encryptedPayload = (string) $request->input('data', '');
        $signature = (string) $request->header('X-Signature', '');

        if ($encryptedPayload === '') {
            return [
                'ok' => false,
                'http_status' => 422,
                'message' => __('Invalid callback format.'),
            ];
        }

        if ($signature !== '' && ! $this->verifyCallbackSignature($encryptedPayload, $signature)) {
            return [
                'ok' => false,
                'http_status' => 403,
                'message' => __('Invalid callback signature.'),
            ];
        }

        $decrypted = $this->decryptCallbackData($encryptedPayload);
        if ($decrypted === null) {
            return [
                'ok' => false,
                'http_status' => 422,
                'message' => __('Unable to decrypt callback payload.'),
            ];
        }

        $data = json_decode($decrypted, true);
        if (! is_array($data)) {
            return [
                'ok' => false,
                'http_status' => 422,
                'message' => __('Invalid decrypted callback payload.'),
                'raw' => $decrypted,
            ];
        }

        return $data;
    }

    private function verifyCallbackSignature(string $encryptedPayload, string $signature): bool
    {
        $signatureSecret = $this->getSignatureSecret();
        if ($signatureSecret === '') {
            return false;
        }

        $expected = hash_hmac('sha256', $encryptedPayload, $signatureSecret);

        return hash_equals(strtolower($expected), strtolower(trim($signature)));
    }

    private function decryptCallbackData(string $encryptedPayload): ?string
    {
        $aesKey = $this->normalizeAesKey($this->getAesKey());
        $aesIv = $this->normalizeAesIv($this->getAesIv());

        if ($aesKey === '' || $aesIv === '') {
            return null;
        }

        // Common case: base64 encoded ciphertext
        $decrypted = openssl_decrypt($encryptedPayload, 'AES-256-CBC', $aesKey, 0, $aesIv);
        if ($decrypted !== false) {
            return $decrypted;
        }

        // Fallback: raw binary ciphertext after base64 decode
        $decoded = base64_decode($encryptedPayload, true);
        if ($decoded === false) {
            return null;
        }

        $decrypted = openssl_decrypt($decoded, 'AES-256-CBC', $aesKey, OPENSSL_RAW_DATA, $aesIv);
        return $decrypted === false ? null : $decrypted;
    }

    private function isWhitelistedIp(?string $ip): bool
    {
        $allowedIps = $this->getWhitelistIps();
        if ($ip === null || $ip === '') {
            return false;
        }
        if ($allowedIps === []) {
            return true;
        }

        return in_array($ip, $allowedIps, true);
    }

    private function getWhitelistIps(): array
    {
        $settings = $this->get_basic_payment_info();
        $raw = (string) ($settings->freshpay_whitelist_ips ?? config('services.freshpay.callback_whitelist', ''));

        return collect(explode(',', $raw))
            ->map(fn ($ip) => trim($ip))
            ->filter(fn ($ip) => $ip !== '')
            ->values()
            ->all();
    }

    private function getSignatureSecret(): string
    {
        $settings = $this->get_basic_payment_info();

        return (string) ($settings->freshpay_callback_signature_secret
            ?? config('services.freshpay.callback_signature_secret')
            ?? $settings->freshpay_merchant_secret
            ?? config('services.freshpay.merchant_secret', ''));
    }

    private function getAesKey(): string
    {
        $settings = $this->get_basic_payment_info();

        return (string) ($settings->freshpay_callback_aes_key
            ?? config('services.freshpay.callback_aes_key', ''));
    }

    private function getAesIv(): string
    {
        $settings = $this->get_basic_payment_info();

        return (string) ($settings->freshpay_callback_aes_iv
            ?? config('services.freshpay.callback_aes_iv', ''));
    }

    private function normalizeAesKey(string $key): string
    {
        if ($key === '') {
            return '';
        }

        $decoded = base64_decode($key, true);
        if ($decoded !== false && strlen($decoded) >= 32) {
            return substr($decoded, 0, 32);
        }

        return substr(str_pad($key, 32, '0'), 0, 32);
    }

    private function normalizeAesIv(string $iv): string
    {
        if ($iv === '') {
            return '';
        }

        $decoded = base64_decode($iv, true);
        if ($decoded !== false && strlen($decoded) >= 16) {
            return substr($decoded, 0, 16);
        }

        return substr(str_pad($iv, 16, '0'), 0, 16);
    }

    private function maskSensitivePayload(array $payload): array
    {
        $payload['merchant_secrete'] = isset($payload['merchant_secrete']) ? '***' : null;
        return $payload;
    }
}
