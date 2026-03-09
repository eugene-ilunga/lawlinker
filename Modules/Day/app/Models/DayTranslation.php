<?php

namespace Modules\Day\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DayTranslation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'title',
        'lang_code',
        'day_id',
    ];

    public function day(): ?BelongsTo
    {
        return $this->belongsTo(Day::class);
    }
}
