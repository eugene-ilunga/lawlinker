<?php

namespace Modules\Lawyer\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DepartmentFaq extends Model {
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['status', 'department_id'];

    public function getQuestionAttribute(): ?string {
        return $this?->translation?->question;
    }

    public function getAnswerAttribute(): ?string {
        return $this?->translation?->answer;
    }

    public function translation(): ?HasOne {
        return $this->hasOne(DepartmentFaqTranslation::class)->where('lang_code', getSessionLanguage());
    }

    public function getTranslation($code): ?DepartmentFaqTranslation {
        return $this->hasOne(DepartmentFaqTranslation::class)->where('lang_code', $code)->first();
    }

    public function translations(): ?HasMany {
        return $this->hasMany(DepartmentFaqTranslation::class, 'department_faq_id');
    }

    public function scopeActive($query) {
        return $query->where('status', 1);
    }

    public function scopeInactive($query) {
        return $query->where('status', 0);
    }

    public function department(): ?BelongsTo {
        return $this->belongsTo(Department::class, 'department_id');
    }
}
