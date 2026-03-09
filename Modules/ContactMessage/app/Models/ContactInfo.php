<?php

namespace Modules\ContactMessage\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ContactInfo extends Model {
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['top_bar_email', 'top_bar_phone', 'email', 'phone', 'address', 'map_embed_code'];
    protected $hidden = ['updated_at','created_at'];

    public function getHeaderAttribute(): ?string {
        return $this?->translation?->header;
    }

    public function getDescriptionAttribute(): ?string {
        return $this?->translation?->description;
    }
    public function getAboutAttribute(): ?string {
        return $this?->translation?->about;
    }
    public function getCopyrightAttribute(): ?string {
        return $this?->translation?->copyright;
    }

    public function translation(): ?HasOne {
        return $this->hasOne(ContactInfoTranslation::class)->where('lang_code', getSessionLanguage());
    }

    public function getTranslation($code): ?ContactInfoTranslation {
        return $this->hasOne(ContactInfoTranslation::class)
            ->where('lang_code', $code)
            ->first();
    }

    public function translations(): ?HasMany {
        return $this->hasMany(ContactInfoTranslation::class, 'contact_info_id');
    }
    public static function boot() {
        parent::boot();

        static::saved(function () {
            cache()->forget('contactInfo');
        });

        static::created(function () {
            cache()->forget('contactInfo');
        });

        static::updated(function () {
            cache()->forget('contactInfo');
        });

        static::deleted(function () {
            cache()->forget('contactInfo');
        });
    }
}
