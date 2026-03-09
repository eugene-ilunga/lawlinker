<?php

namespace Modules\PaymentWithdraw\app\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Lawyer\app\Models\Lawyer;

class WithdrawRequest extends Model {
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['lawyer_id', 'withdraw_method_id', 'current_balance','total_amount', 'withdraw_amount', 'withdraw_charge', 'account_info', 'status', 'approved_date'];

    public function lawyer() {
        return $this->belongsTo(Lawyer::class,'lawyer_id')->select('id', 'name', 'email', 'image', 'wallet_balance');
    }
    public function withdraw_method() {
        return $this->belongsTo(WithdrawMethod::class,'withdraw_method_id');
    }
    public function scopeActive($query) {
        return $query->where('status', 'approved');
    }
    public function scopePending($query) {
        return $query->where('status', 'pending');
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
