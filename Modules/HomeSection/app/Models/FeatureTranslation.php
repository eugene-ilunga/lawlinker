<?php

namespace Modules\HomeSection\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FeatureTranslation extends Model
{
    use HasFactory;

    protected $fillable = ['feature_id','lang_code','title','description'];

    public function feature(): ?BelongsTo {
        return $this->belongsTo(Feature::class, 'feature_id');
    }
}
