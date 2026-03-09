<?php

namespace Modules\HomeSection\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CounterTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'counter_id',
        'lang_code',
        'title',
    ];

    public function counter(): ?BelongsTo
    {
        return $this->belongsTo(Counter::class, 'counter_id');
    }
}
