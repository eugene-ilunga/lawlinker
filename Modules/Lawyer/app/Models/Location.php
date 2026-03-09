<?php

namespace Modules\Lawyer\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Location extends Model {
    use HasFactory;

    protected $fillable = ['status'];
    protected $hidden = ['updated_at','created_at'];

    public function getNameAttribute(): ?string {
        return $this?->translation?->name;
    }

    public function translation(): ?HasOne {
        return $this->hasOne(LocationTranslation::class)->where('lang_code', getSessionLanguage());
    }

    public function getTranslation($code): ?LocationTranslation {
        return $this->hasOne(LocationTranslation::class)->where('lang_code', $code)->first();
    }

    public function translations(): ?HasMany {
        return $this->hasMany(LocationTranslation::class, 'location_id');
    }

    public function scopeActive($query) {
        return $query->where('status', 1);
    }
    public function lawyers() {
        return $this->hasMany(Lawyer::class, 'location_id');
    }
}
