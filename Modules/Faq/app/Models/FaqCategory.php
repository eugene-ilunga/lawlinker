<?php

namespace Modules\Faq\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class FaqCategory extends Model {
    use HasFactory;

    protected $fillable = ['slug', 'status'];

    // make a accessor for translation
    public function getTitleAttribute(): ?string {
        return $this?->translation?->title;
    }
    public function translation(): ?HasOne {
        return $this->hasOne(FaqCategoryTranslation::class)->where('lang_code', getSessionLanguage());
    }

    public function getTranslation($code): ?FaqCategoryTranslation {
        return $this->hasOne(FaqCategoryTranslation::class)->where('lang_code', $code)->first();
    }

    public function translations(): ?HasMany {
        return $this->hasMany(FaqCategoryTranslation::class, 'faq_category_id');
    }

    public function faq_list() {
        return $this->hasMany(Faq::class, 'faq_category_id');
    }
    public function scopeActive($query) {
        return $query->where('status', 1);
    }
}
