<?php

namespace Modules\HomeSection\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class WorkSectionFaq extends Model {
    use HasFactory;

    protected $fillable = [
        'work_section_id',
        'status',
    ];

    public function getQuestionAttribute(): ?string {
        return $this?->translation?->question;
    }

    public function getAnswerAttribute(): ?string {
        return $this?->translation?->answer;
    }

    public function translation(): ?HasOne {
        return $this->hasOne(WorkSectionFaqTranslation::class)->where('lang_code', getSessionLanguage());
    }

    public function getTranslation($code): ?WorkSectionFaqTranslation {
        return $this->hasOne(WorkSectionFaqTranslation::class)->where('lang_code', $code)->first();
    }

    public function translations(): ?HasMany {
        return $this->hasMany(WorkSectionFaqTranslation::class, 'work_section_faq_id');
    }

    public function scopeActive($query) {
        return $query->where('status', 1);
    }
}
