<?php

namespace Modules\Lawyer\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DepartmentFaqTranslation extends Model {
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'department_faq_id',
        'lang_code',
        'question',
        'answer',
    ];

    public function department_faq(): ?BelongsTo {
        return $this->belongsTo(DepartmentFaq::class, 'department_faq_id');
    }
}
