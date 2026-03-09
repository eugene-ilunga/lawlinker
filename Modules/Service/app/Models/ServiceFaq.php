<?php

namespace Modules\Service\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ServiceFaq extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['status', 'service_id'];

    public function getQuestionAttribute(): ?string {
        return $this?->translation?->question;
    }

    public function getAnswerAttribute(): ?string {
        return $this?->translation?->answer;
    }

    public function translation(): ?HasOne {
        return $this->hasOne(ServiceFaqTranslation::class)->where('lang_code', getSessionLanguage());
    }

    public function getTranslation($code): ?ServiceFaqTranslation {
        return $this->hasOne(ServiceFaqTranslation::class)->where('lang_code', $code)->first();
    }

    public function translations(): ?HasMany {
        return $this->hasMany(ServiceFaqTranslation::class, 'service_faq_id');
    }

    public function scopeActive($query) {
        return $query->where('status', 1);
    }

    public function scopeInactive($query) {
        return $query->where('status', 0);
    }

    public function service(): ?BelongsTo {
        return $this->belongsTo(Service::class, 'service_id');
    }
}
