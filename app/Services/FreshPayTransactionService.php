<?php

namespace App\Services;

use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\BasicPayment\app\Models\FreshPayTransaction;
use Modules\Lawyer\app\Models\Lawyer;
use Modules\Order\app\Models\Order;

class FreshPayTransactionService
{
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_SUCCESS = 'success';
    public const STATUS_FAILED = 'failed';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUS_ERROR = 'error';

    private const CACHE_TTL_SECONDS = 86400;

    public function normalizeStatus(?string $status): string
    {
        $status = strtolower(trim((string) $status));

        return match ($status) {
            'success', 'paid', 'completed', 'complete', 'approved' => self::STATUS_SUCCESS,
            'cancelled', 'canceled', 'cancel' => self::STATUS_CANCELLED,
            'failed', 'fail', 'declined', 'rejected', 'error' => self::STATUS_FAILED,
            default => self::STATUS_PROCESSING,
        };
    }

    public function createPendingTransaction(Order $order, ?int $userId, array $attributes): object
    {
        $payload = [
            'order_db_id' => $order->id,
            'order_public_id' => $order->order_id,
            'user_id' => $userId,
            'reference' => $attributes['reference'],
            'channel' => $attributes['channel'] ?? 'web',
            'customer_number' => $attributes['customer_number'] ?? null,
            'operator' => $attributes['operator'] ?? null,
            'amount' => $attributes['amount'] ?? 0,
            'currency' => $attributes['currency'] ?? 'USD',
            'status' => self::STATUS_PROCESSING,
            'message' => $attributes['message'] ?? __('Payment request sent to FreshPay.'),
            'request_payload' => $attributes['request_payload'] ?? null,
            'response_payload' => $attributes['response_payload'] ?? null,
            'callback_payload' => null,
            'finalized_at' => null,
            'completed_at' => null,
        ];

        try {
            if ($this->tableExists()) {
                return FreshPayTransaction::create($payload);
            }
        } catch (QueryException) {
        }

        $this->storeOnOrder($order, $payload);

        $this->storeInCache($payload['reference'], $payload);

        return $this->hydrateCachedTransaction($payload);
    }

    public function updateFromCallback(array $result): ?object
    {
        $reference = (string) ($result['reference'] ?? '');
        if ($reference === '') {
            return null;
        }

        $transaction = $this->findByReference($reference);
        if (! $transaction) {
            return null;
        }

        $status = $this->normalizeStatus($result['status'] ?? null);
        $message = (string) ($result['message'] ?? '');
        if ($message === '' || $message === __('Callback processed')) {
            $message = $this->defaultMessageForStatus($status);
        }

        $updates = [
            'status' => $status,
            'message' => $message,
            'callback_payload' => $result['data'] ?? null,
            'completed_at' => $status === self::STATUS_PROCESSING ? null : now(),
        ];

        return $this->persistTransactionUpdate($transaction, $updates);
    }

    public function finalizeSuccessfulTransaction(object $transaction): bool
    {
        if ($transaction->status !== self::STATUS_SUCCESS) {
            return false;
        }

        if ($transaction->finalized_at) {
            return true;
        }

        $order = Order::with(['appointments.lawyer', 'user'])->find($transaction->order_db_id);
        if (! $order) {
            return false;
        }

        try {
            DB::beginTransaction();

            $alreadyPaid = (int) $order->payment_status === 1;
            $paymentDetails = $transaction->callback_payload ?: $transaction->response_payload ?: [];

            $order->payment_status = 1;
            $order->order_status = 1;
            $order->payment_transaction_id = $transaction->reference;
            $order->payment_description = is_string($paymentDetails) ? $paymentDetails : json_encode($paymentDetails);
            $order->approved_date = $order->approved_date ?: now();
            $order->save();

            $order->appointments()->update([
                'payment_status' => 1,
                'payment_transaction_id' => $transaction->reference,
                'payment_description' => is_string($paymentDetails) ? $paymentDetails : json_encode($paymentDetails),
            ]);

            if (! $alreadyPaid) {
                foreach ($order->appointments as $appointment) {
                    Lawyer::where('id', $appointment?->lawyer?->id)->increment('wallet_balance', $appointment->appointment_fee_usd);
                }
            }

            $this->persistTransactionUpdate($transaction, ['finalized_at' => now()]);

            DB::commit();

            return true;
        } catch (Exception) {
            DB::rollBack();
            return false;
        }
    }

    public function defaultMessageForStatus(string $status): string
    {
        return match ($status) {
            self::STATUS_SUCCESS => __('Payment confirmed by FreshPay.'),
            self::STATUS_CANCELLED => __('Payment cancelled by the user.'),
            self::STATUS_FAILED => __('Payment failed at FreshPay.'),
            self::STATUS_ERROR => __('A payment error occurred.'),
            default => __('Payment is being processed by FreshPay.'),
        };
    }

    public function findByReference(string $reference): ?object
    {
        try {
            if ($this->tableExists()) {
                $transaction = FreshPayTransaction::where('reference', $reference)->first();
                if ($transaction) {
                    return $transaction;
                }
            }
        } catch (QueryException) {
        }

        $order = Order::where('payment_transaction_id', $reference)->first();
        if ($order) {
            return $this->hydrateOrderTransaction($order);
        }

        $payload = Cache::get($this->cacheKey($reference));

        return is_array($payload) ? $this->hydrateCachedTransaction($payload) : null;
    }

    private function persistTransactionUpdate(object $transaction, array $updates): object
    {
        if ($transaction instanceof FreshPayTransaction) {
            $transaction->update($updates);
            return $transaction->fresh();
        }

        if (isset($transaction->order_db_id)) {
            $order = Order::find($transaction->order_db_id);
            if ($order) {
                $payload = array_merge((array) $transaction, $updates);
                $this->storeOnOrder($order, $payload);
                $this->storeInCache((string) $transaction->reference, $payload);

                return $this->hydrateOrderTransaction($order->fresh());
            }
        }

        $payload = array_merge((array) $transaction, $updates);
        $this->storeInCache((string) $transaction->reference, $payload);

        return $this->hydrateCachedTransaction($payload);
    }

    private function tableExists(): bool
    {
        static $exists;

        if ($exists !== null) {
            return $exists;
        }

        try {
            $exists = Schema::hasTable('freshpay_transactions');
        } catch (Exception) {
            $exists = false;
        }

        return $exists;
    }

    private function storeInCache(string $reference, array $payload): void
    {
        Cache::put($this->cacheKey($reference), $payload, now()->addSeconds(self::CACHE_TTL_SECONDS));
    }

    private function cacheKey(string $reference): string
    {
        return 'freshpay_transaction:'.$reference;
    }

    private function hydrateCachedTransaction(array $payload): object
    {
        $transaction = (object) $payload;
        $transaction->order = Order::find($payload['order_db_id'] ?? null);

        return $transaction;
    }

    private function storeOnOrder(Order $order, array $payload): void
    {
        $existingDescription = $this->decodeOrderPaymentDescription($order);
        $existingDescription['freshpay_tracking'] = [
            'order_db_id' => $payload['order_db_id'] ?? $order->id,
            'order_public_id' => $payload['order_public_id'] ?? $order->order_id,
            'user_id' => $payload['user_id'] ?? $order->user_id,
            'reference' => $payload['reference'] ?? $order->payment_transaction_id,
            'channel' => $payload['channel'] ?? 'web',
            'customer_number' => $payload['customer_number'] ?? null,
            'operator' => $payload['operator'] ?? null,
            'amount' => $payload['amount'] ?? 0,
            'currency' => $payload['currency'] ?? 'USD',
            'status' => $payload['status'] ?? self::STATUS_PROCESSING,
            'message' => $payload['message'] ?? null,
            'request_payload' => $payload['request_payload'] ?? null,
            'response_payload' => $payload['response_payload'] ?? null,
            'callback_payload' => $payload['callback_payload'] ?? null,
            'finalized_at' => $payload['finalized_at'] ?? null,
            'completed_at' => $payload['completed_at'] ?? null,
        ];

        $order->payment_transaction_id = $payload['reference'] ?? $order->payment_transaction_id;
        $order->payment_description = json_encode($existingDescription);
        $order->save();
    }

    private function hydrateOrderTransaction(Order $order): object
    {
        $description = $this->decodeOrderPaymentDescription($order);
        $tracking = is_array($description['freshpay_tracking'] ?? null) ? $description['freshpay_tracking'] : [];

        $transaction = (object) array_merge([
            'order_db_id' => $order->id,
            'order_public_id' => $order->order_id,
            'user_id' => $order->user_id,
            'reference' => $order->payment_transaction_id,
            'channel' => 'web',
            'customer_number' => null,
            'operator' => null,
            'amount' => $order->paid_amount,
            'currency' => $order->payable_currency ?? 'USD',
            'status' => self::STATUS_PROCESSING,
            'message' => null,
            'request_payload' => null,
            'response_payload' => null,
            'callback_payload' => null,
            'finalized_at' => null,
            'completed_at' => null,
        ], $tracking);

        $transaction->order = $order;

        return $transaction;
    }

    private function decodeOrderPaymentDescription(Order $order): array
    {
        if (! $order->payment_description) {
            return [];
        }

        $decoded = json_decode($order->payment_description, true);

        return is_array($decoded) ? $decoded : [];
    }
}
