<?php

namespace Modules\ContactMessage\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContactInfoTranslation extends Model {
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'contact_info_id',
        'lang_code',
        'header',
        'description',
        'about',
        'copyright',
    ];
    public function contact_info(): ?BelongsTo {
        return $this->belongsTo(ContactInfo::class, 'contact_info_id');
    }
    public static function boot() {
        parent::boot();

        static::saved(function () {
            cache()->forget('contactInfo');
        });

        static::created(function () {
            cache()->forget('contactInfo');
        });

        static::updated(function () {
            cache()->forget('contactInfo');
        });

        static::deleted(function () {
            cache()->forget('contactInfo');
        });
    }

}
