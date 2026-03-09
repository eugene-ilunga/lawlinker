<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\MeetingHistory;

class MeetingController extends Controller {
    public function meetingHistory() {
        $user = userAuth();
        $now = now();
        $histories = MeetingHistory::where('user_id', $user->id)
            ->whereRaw('DATE_ADD(meeting_time, INTERVAL duration MINUTE) < ?', [$now])
            ->orderBy('meeting_time', 'asc')
            ->paginate(10);
        return view('client.profile.meeting-history', compact('histories', 'user'));
    }

    public function upCommingMeeting() {
        $user = userAuth();
        $now = now();
        $histories = MeetingHistory::where('user_id', $user->id)
            ->whereRaw('DATE_ADD(meeting_time, INTERVAL duration MINUTE) > ?', [$now])
            ->orderBy('meeting_time', 'asc')
            ->paginate(10);
        return view('client.profile.upcoming-meeting', compact('histories', 'user'));
    }
}
