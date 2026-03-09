<?php

namespace Modules\PaymentWithdraw\app\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WithdrawMethod extends Model {
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['status'];
    public function withdraw_request(): ?HasMany {
        return $this->hasMany(WithdrawRequest::class, 'withdraw_method_id');
    }
    public function scopeActive($query) {
        return $query->where('status', 'active');
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
