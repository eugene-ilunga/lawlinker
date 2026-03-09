<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Lawyer\app\Models\Lawyer;

class Message extends Model {
    use HasFactory;
    protected $fillable = [
        'lawyer_id', 'user_id', 'message', 'lawyer_view', 'client_view', 'send_lawyer', 'send_user',
    ];

    public function lawyer(): BelongsTo {
        return $this->belongsTo(Lawyer::class, 'lawyer_id');
    }

    public function client(): BelongsTo {
        return $this->belongsTo(Lawyer::class, 'user_id');
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
