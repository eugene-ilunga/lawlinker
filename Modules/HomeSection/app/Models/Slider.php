<?php

namespace Modules\HomeSection\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model {
    use HasFactory;

    protected $fillable = ['image', 'title', 'status'];

    public function scopeActive($query) {
        return $query->where('status', 1);
    }
}
