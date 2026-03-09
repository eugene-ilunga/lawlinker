<?php

namespace Modules\HomeSection\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Counter extends Model {
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'icon',
        'qty',
        'status',
    ];

    public function getTitleAttribute(): ?string {
        return $this?->translation?->title;
    }

    public function translation(): ?HasOne {
        return $this->hasOne(CounterTranslation::class)->where('lang_code', getSessionLanguage());
    }

    public function getTranslation($code): ?CounterTranslation {
        return $this->hasOne(CounterTranslation::class)->where('lang_code', $code)->first();
    }

    public function translations(): ?HasMany {
        return $this->hasMany(CounterTranslation::class, 'counter_id');
    }
    public function scopeActive($query) {
        return $query->where('status', 1);
    }
}
