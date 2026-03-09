<?php

namespace Modules\HomeSection\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkSectionFaqTranslation extends Model {
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'work_section_faq_id',
        'lang_code',
        'question',
        'answer',
    ];

    public function work_section_faq(): ?BelongsTo {
        return $this->belongsTo(WorkSectionFaq::class, 'work_section_faq_id');
    }

}
