<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Lawyer\app\Models\Lawyer;

class ZoomCredential extends Model {
    use HasFactory;
    protected $fillable = ['lawyer_id', 'zoom_account_id','zoom_api_key', 'zoom_api_secret'];
    protected $hidden = ['updated_at','created_at'];
    public function lawyer(): ?BelongsTo {
        return $this->belongsTo(Lawyer::class, 'lawyer_id');
    }
}
