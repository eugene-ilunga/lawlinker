<?php

namespace Modules\App\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnBoardingScreen extends Model {
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['title', 'sort_description', 'image', 'order', 'status'];
    public function scopeActive($query) {
        return $query->where('status', 1);
    }
    protected $hidden = [
        'updated_at','created_at','status'
    ];
}
