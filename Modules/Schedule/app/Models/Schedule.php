<?php

namespace Modules\Schedule\app\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Appointment\app\Models\Appointment;
use Modules\Day\app\Models\Day;
use Modules\Lawyer\app\Models\Lawyer;

class Schedule extends Model {
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'day_id', 'lawyer_id', 'start_time', 'end_time', 'quantity', 'status',
    ];
    protected $hidden = ['updated_at', 'created_at'];

    public function day() {
        return $this->belongsTo(Day::class);
    }

    public function lawyer() {
        return $this->belongsTo(Lawyer::class);
    }
    public function appointments(): ?HasMany {
        return $this->hasMany(Appointment::class, 'schedule_id');
    }
    public function scopeActive($query) {
        return $query->where('status', 1);
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
