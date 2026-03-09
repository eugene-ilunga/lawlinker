<?php

namespace Modules\Blog\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BlogTranslation extends Model {
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'blog_id',
        'lang_code',
        'title',
        'sort_description',
        'description',
        'seo_title',
        'seo_description',
    ];

    public function post(): ?BelongsTo {
        return $this->belongsTo(Blog::class, 'blog_id');
    }
}
