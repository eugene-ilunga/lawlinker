<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Lawyer\app\Models\Lawyer;

class ZoomMeeting extends Model {
    use HasFactory;
    protected $fillable = [
        'lawyer_id', 'topic', 'start_time', 'duration', 'meeting_id', 'password', 'join_url',
    ];
    protected $hidden = ['lawyer_id', 'updated_at', 'created_at'];

    public function lawyer() {
        return $this->belongsTo(Lawyer::class);
    }
    public function meeting_history(): ?HasMany {
        return $this->hasMany(MeetingHistory::class, 'meeting_id');
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
