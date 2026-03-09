<?php

namespace Modules\Lawyer\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LocationTranslation extends Model {
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['location_id', 'lang_code', 'name'];
    protected $hidden = ['updated_at','created_at'];

    public function location(): ?BelongsTo {
        return $this->belongsTo(Location::class, 'location_id');
    }
}
