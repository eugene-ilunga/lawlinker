<?php

namespace Modules\Order\app\Models;

use App\Models\User;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Appointment\app\Models\Appointment;

class Order extends Model {
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['order_id','user_id','appointment_qty','payment_method','payment_status', 'amount_usd','payable_amount','total_payment', 'payment_description', 'payable_with_charge', 'gateway_charge','payable_currency','paid_amount','payment_transaction_id','order_status'];
    protected $hidden = ['user_id', 'amount_usd', 'payment_description', 'payable_with_charge', 'gateway_charge', 'show_notification', 'updated_at', 'created_at'];

    public function user() {
        return $this->belongsTo(User::class)->select('id', 'name', 'email', 'image');
    }
    public function appointments(): ?HasMany {
        return $this->hasMany(Appointment::class, 'order_id');
    }
    public function scopeSuccessOrder($query) {
        return $query->where('order_status', 1);
    }
    public function scopePendingOrder($query) {
        return $query->where('order_status', 0);
    }
    public function scopePaymentSuccess($query) {
        return $query->where('payment_status', 1);
    }
    public function scopePaymentPending($query) {
        return $query->where('payment_status', 0);
    }
    /**
     * The boot method of the model.
     */
    protected static function boot() {
        parent::boot();
        static::deleting(function ($order) {
            $order->appointments()->delete();
        });
    }
    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date) {
        return $date->format('Y-m-d H:i:s');
    }
}
