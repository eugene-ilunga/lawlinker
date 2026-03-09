<?php

namespace Modules\NewsLetter\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubscriberContentTranslation extends Model {
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'subscriber_content_id',
        'lang_code',
        'title',
        'description',
    ];
    public function subscriber_content(): ?BelongsTo {
        return $this->belongsTo(SubscriberContent::class, 'subscriber_content_id');
    }
}
