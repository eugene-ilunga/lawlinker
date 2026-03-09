<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Lawyer\app\Models\Lawyer;

class LawyerSocialMedia extends Model {
    use HasFactory;
    protected $fillable = ['lawyer_id', 'link', 'icon', 'status'];
    public function lawyer(): ?BelongsTo {
        return $this->belongsTo(Lawyer::class, 'lawyer_id');
    }
    public function scopeActive($query) {
        return $query->where('status', 1);
    }
}
