<?php

namespace Modules\HomeSection\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class WorkSection extends Model {
    use HasFactory;

    protected $fillable = ['image', 'video', 'status'];

    public function getTitleAttribute(): ?string {
        return $this?->translation?->title;
    }
    public function translation(): ?HasOne {
        return $this->hasOne(WorkSectionTranslation::class)->where('lang_code', getSessionLanguage());
    }

    public function getTranslation($code): ?WorkSectionTranslation {
        return $this->hasOne(WorkSectionTranslation::class)
            ->where('lang_code', $code)
            ->first();
    }

    public function translations(): ?HasMany {
        return $this->hasMany(WorkSectionTranslation::class, 'work_section_id');
    }
    public function scopeActive($query) {
        return $query->where('status', 1);
    }
    public function faqs(): ?HasMany {
        return $this->hasMany(WorkSectionFaq::class, 'work_section_id');
    }
}
