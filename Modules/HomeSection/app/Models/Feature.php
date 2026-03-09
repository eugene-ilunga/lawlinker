<?php

namespace Modules\HomeSection\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Feature extends Model {
    use HasFactory;

    protected $fillable = ['status', 'image', 'icon'];

    public function getDescriptionAttribute(): ?string {
        return $this?->translation?->description;
    }

    public function getTitleAttribute(): ?string {
        return $this?->translation?->title;
    }

    public function translation(): ?HasOne {
        return $this->hasOne(FeatureTranslation::class)->where('lang_code', getSessionLanguage());
    }

    public function getTranslation($code): ?FeatureTranslation {
        return $this->hasOne(FeatureTranslation::class)->where('lang_code', $code)->first();
    }

    public function translations(): ?HasMany {
        return $this->hasMany(FeatureTranslation::class, 'feature_id');
    }
    public function scopeActive($query) {
        return $query->where('status', 1);
    }
}
