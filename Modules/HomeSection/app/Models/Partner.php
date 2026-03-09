<?php

namespace Modules\HomeSection\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model {
    use HasFactory;

    protected $fillable = ['image', 'link', 'status'];

    public function scopeActive($query) {
        return $query->where('status', 1);
    }
}
