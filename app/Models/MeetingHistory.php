<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Lawyer\app\Models\Lawyer;

class MeetingHistory extends Model {
    use HasFactory;
    protected $fillable = [
        'lawyer_id', 'user_id', 'meeting_id', 'meeting_time', 'duration',
    ];
    protected $hidden = ['lawyer_id', 'user_id', 'meeting_id', 'updated_at', 'created_at'];
    public function user() {
        return $this->belongsTo(User::class);
    }

    public function meeting() {
        return $this->belongsTo(ZoomMeeting::class, 'meeting_id', 'meeting_id');
    }

    public function lawyer() {
        return $this->belongsTo(Lawyer::class);
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
