<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\DB;
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

    public function createPendingTransaction(Order $order, ?int $userId, array $attributes): FreshPayTransaction
    {
        return FreshPayTransaction::create([
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
        ]);
    }

    public function updateFromCallback(array $result): ?FreshPayTransaction
    {
        $reference = (string) ($result['reference'] ?? '');
        if ($reference === '') {
            return null;
        }

        $transaction = FreshPayTransaction::where('reference', $reference)->first();
        if (! $transaction) {
            return null;
        }

        $status = $this->normalizeStatus($result['status'] ?? null);
        $message = (string) ($result['message'] ?? '');
        if ($message === '' || $message === __('Callback processed')) {
            $message = $this->defaultMessageForStatus($status);
        }

        $transaction->update([
            'status' => $status,
            'message' => $message,
            'callback_payload' => $result['data'] ?? null,
            'completed_at' => $status === self::STATUS_PROCESSING ? null : now(),
        ]);

        return $transaction->fresh();
    }

    public function finalizeSuccessfulTransaction(FreshPayTransaction $transaction): bool
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

            $transaction->update(['finalized_at' => now()]);

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
}
