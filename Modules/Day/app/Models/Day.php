<?php

namespace Modules\Day\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\Appointment\app\Models\Appointment;
use Modules\Schedule\app\Models\Schedule;

class Day extends Model {
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['slug','status'];

    public function getTitleAttribute(): ?string {
        return $this?->translation?->title;
    }

    public function translation(): ?HasOne {
        return $this->hasOne(DayTranslation::class)->where('lang_code', getSessionLanguage());
    }

    public function getTranslation($code): ?DayTranslation {
        return $this->hasOne(DayTranslation::class)->where('lang_code', $code)->first();
    }

    public function translations(): ?HasMany {
        return $this->hasMany(DayTranslation::class, 'day_id');
    }
    public function schedules(): ?HasMany {
        return $this->hasMany(Schedule::class, 'day_id');
    }
    public function appointments(): ?HasMany {
        return $this->hasMany(Appointment::class, 'day_id');
    }
    public function scopeActive($query) {
        return $query->where('status', 1);
    }
}
