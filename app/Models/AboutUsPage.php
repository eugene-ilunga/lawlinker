<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class AboutUsPage extends Model {
    use HasFactory;

    protected $fillable = [
        'status',
        'about_image',
        'background_image',
        'mission_image',
        'mission_status',
        'vision_image',
        'vision_status',
    ];

    public function getAboutDescriptionAttribute(): ?string {
        return $this?->translation?->about_description;
    }

    public function getMissionDescriptionAttribute(): ?string {
        return $this?->translation?->mission_description;
    }
    public function getVisionDescriptionAttribute(): ?string {
        return $this?->translation?->vision_description;
    }

    public function translation(): ?HasOne {
        return $this->hasOne(AboutUsPageTranslation::class)->where('lang_code', getSessionLanguage());
    }

    public function getTranslation($code): ?AboutUsPageTranslation {
        return $this->hasOne(AboutUsPageTranslation::class)
            ->where('lang_code', $code)
            ->first();
    }

    public function translations(): ?HasMany {
        return $this->hasMany(AboutUsPageTranslation::class, 'about_us_page_id');
    }
    public function scopeActive($query) {
        return $query->where('status', 1);
    }
}
