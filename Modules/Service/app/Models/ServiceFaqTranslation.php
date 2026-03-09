<?php

namespace Modules\Service\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceFaqTranslation extends Model {
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'service_faq_id',
        'lang_code',
        'question',
        'answer',
    ];

    public function service_faq(): ?BelongsTo {
        return $this->belongsTo(ServiceFaq::class, 'service_faq_id');
    }
}
