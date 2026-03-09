<?php

namespace Modules\PageBuilder\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class CustomizablePageTranslation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'customizeable_page_id',
        'lang_code',
        'title',
        'description',
    ];

    public function customizeablePage() {
        return $this->belongsTo(CustomizeablePage::class,'customizeable_page_id');
    }

    public static function boot()
    {
        parent::boot();

        static::saved(function () {
            Cache::forget('customPages');
        });

        static::created(function () {
            Cache::forget('customPages');
        });

        static::updated(function () {
            Cache::forget('customPages');
        });

        static::deleted(function () {
            Cache::forget('customPages');
        });
    }
}
