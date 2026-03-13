<?php

namespace Modules\BasicPayment\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Order\app\Models\Order;

class FreshPayTransaction extends Model
{
    use HasFactory;

    protected $table = 'freshpay_transactions';

    protected $fillable = [
        'order_db_id',
        'order_public_id',
        'user_id',
        'reference',
        'channel',
        'customer_number',
        'operator',
        'amount',
        'currency',
        'status',
        'message',
        'request_payload',
        'response_payload',
        'callback_payload',
        'finalized_at',
        'completed_at',
    ];

    protected $casts = [
        'request_payload' => 'array',
        'response_payload' => 'array',
        'callback_payload' => 'array',
        'finalized_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_db_id');
    }
}
