<?php

namespace Modules\Blog\app\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BlogComment extends Model {
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'phone',
        'comment',
        'name',
        'blog_id',
        'email',
        'status',
    ];

    public function post(): ?BelongsTo {
        return $this->belongsTo(Blog::class, 'blog_id');
    }
    public function scopeActive($query) {
        return $query->where('status', 1);
    }

    public function scopeInactive($query) {
        return $query->where('status', 0);
    }
    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date) {
        return $date->format('Y-m-d H:i:s');
    }
}
