<?php

namespace Modules\NewsLetter\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\NewsLetter\app\Models\SubscriberContentTranslation;

class SubscriberContent extends Model {
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['image'];

    public function getTitleAttribute(): ?string {
        return $this?->translation?->title;
    }

    public function getDescriptionAttribute(): ?string {
        return $this?->translation?->description;
    }

    public function translation(): ?HasOne {
        return $this->hasOne(SubscriberContentTranslation::class)->where('lang_code', getSessionLanguage());
    }

    public function getTranslation($code): ?SubscriberContentTranslation {
        return $this->hasOne(SubscriberContentTranslation::class)
            ->where('lang_code', $code)
            ->first();
    }

    public function translations(): ?HasMany {
        return $this->hasMany(SubscriberContentTranslation::class, 'subscriber_content_id');
    }
}
