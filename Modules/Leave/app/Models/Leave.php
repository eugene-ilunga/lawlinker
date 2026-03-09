<?php

namespace Modules\Leave\app\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Lawyer\app\Models\Lawyer;

class Leave extends Model {
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['lawyer_id', 'date', 'reason', 'status'];
    public function lawyer(): ?BelongsTo {
        return $this->belongsTo(Lawyer::class, 'lawyer_id');
    }
    public function scopeApproved($query) {
        return $query->where('status', 1);
    }
    public function scopePending($query) {
        return $query->where('status', 0);
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
