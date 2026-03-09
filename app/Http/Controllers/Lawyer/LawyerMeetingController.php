<?php

namespace App\Http\Controllers\Lawyer;

use App\Enums\RedirectType;
use App\Http\Controllers\Controller;
use App\Models\MeetingHistory;
use App\Models\User;
use App\Models\ZoomCredential;
use App\Models\ZoomMeeting;
use App\Services\Zoom;
use App\Traits\GlobalMailTrait;
use App\Traits\RedirectHelperTrait;
use Exception;
use Illuminate\Http\Request;
use Modules\Appointment\app\Models\Appointment;

class LawyerMeetingController extends Controller {
    use GlobalMailTrait, RedirectHelperTrait;

    public function index() {
        $lawyer = lawyerAuth();
        $meetings = ZoomMeeting::where('lawyer_id', $lawyer->id)->orderBy('start_time', 'desc')->paginate(10);
        $confirm = __('Are you sure?');
        return view('lawyer.zoom.meeting.index', compact('meetings', 'confirm'));
    }
    public function create(Request $request) {
        $lawyer = lawyerAuth();
        $credential = ZoomCredential::where('lawyer_id', $lawyer->id)->first();
        if (!$credential) {
            $notification = ['message' => __('Please setup the credentials'), 'alert-type' => 'error'];
            return to_route('lawyer.zoom-credential')->with($notification);
        }

        $clients = Appointment::where('lawyer_id', $lawyer->id)->select('user_id')->groupBy('user_id')->get();
        $clientIds = $clients->pluck('user_id');
        $users = User::whereIn('id', $clientIds)->get();
        return view('lawyer.zoom.meeting.create', compact('clients', 'users'));
    }

    public function store(Request $request) {
        $rules = [
            'topic'      => 'required',
            'start_time' => 'required',
            'duration'   => 'required|numeric',
            'client'    => 'required',
        ];

        $customMessages = [
            'topic.required'      => __('Topic is required'),
            'start_time.required' => __('Start time is required'),
            'duration.required'   => __('Duration is required'),
            'client.required'    => __('Client is required'),
        ];

        $this->validate($request, $rules, $customMessages);

        $zoom = new Zoom;
        $dataFormat = $zoom->dataFormat($request->topic, $request->duration, $request->start_time);
        $response = $zoom->createMeeting($dataFormat);

        if ($response['status'] == true) {
            $data = $response['data'];

            $lawyer = lawyerAuth();
            $start_time = date('Y-m-d H:i:s', strtotime($data['start_time']));
            $topic = $data['topic'];
            $duration = $data['duration'];
            $meeting_id = $data['id'];
            $password = $data['password'];
            $join_url = $data['join_url'];

            $meeting = new ZoomMeeting();
            $meeting->lawyer_id = $lawyer->id;
            $meeting->start_time = $start_time;
            $meeting->topic = $topic;
            $meeting->duration = $duration;
            $meeting->meeting_id = $meeting_id;
            $meeting->password = $password;
            $meeting->join_url = $join_url;
            $meeting->save();

            if ($request->client == -1) {
                $appointments = Appointment::where('lawyer_id', $lawyer->id)->select('user_id')->groupBy('user_id')->get();
                $clientIds = $appointments->pluck('user_id');
                $users = User::whereIn('id', $clientIds)->get();

                foreach ($users as $user) {
                    $history = new MeetingHistory();
                    $history->lawyer_id = $lawyer->id;
                    $history->user_id = $user->id;
                    $history->meeting_id = $meeting_id;
                    $history->meeting_time = $start_time;
                    $history->duration = $duration;
                    $history->save();

                    // send email
                    try {
                        [$subject, $message] = $this->fetchEmailTemplate('zoom_meeting', ['client_name' => $user?->name, 'lawyer_name' => $lawyer?->name, 'meeting_schedule' => $start_time]);
                        $link = [__('Meeting Link') => $join_url];
                        $this->sendMail($user->email, $subject, $message, $link);
                    } catch (Exception $e) {
                        info($e->getMessage());
                    }
                }
            } else {

                $user = User::where('id', $request->client)->first();

                $history = new MeetingHistory();
                $history->lawyer_id = $lawyer->id;
                $history->user_id = $user->id;
                $history->meeting_id = $meeting_id;
                $history->meeting_time = $start_time;
                $history->duration = $duration;
                $history->save();

                // send email
                try {
                    [$subject, $message] = $this->fetchEmailTemplate('zoom_meeting', ['client_name' => $user?->name, 'lawyer_name' => $lawyer?->name, 'meeting_schedule' => $start_time]);
                    $link = [__('Meeting Link') => $join_url];
                    $this->sendMail($user->email, $subject, $message, $link);
                } catch (Exception $e) {
                    info($e->getMessage());
                }
            }

            return $this->redirectWithMessage(RedirectType::CREATE->value, 'lawyer.zoom-meetings');
        }
        $notification = ['message' => __('Invalid Credentials') . ' ' . __('Please setup the credentials'), 'alert-type' => 'error'];
        return to_route('lawyer.zoom-credential')->with($notification);

    }

    public function edit($id) {
        $meeting = ZoomMeeting::where('meeting_id', $id)->first();
        if ($meeting) {
            $meeting_user_id = $this->getUserIdOrZero($id);
            $lawyer = lawyerAuth();
            if ($meeting->lawyer_id) {
                $lawyer = lawyerAuth();
                $clients = Appointment::where('lawyer_id', $lawyer->id)->select('user_id')->groupBy('user_id')->get();
                $clientIds = $clients->pluck('user_id');
                $users = User::whereIn('id', $clientIds)->get();
                return view('lawyer.zoom.meeting.edit', compact('meeting', 'users', 'meeting_user_id'));
            } else {
                $notification = ['message' => __('Something went wrong'), 'alert-type' => 'error'];
                return to_route('lawyer.zoom-meetings')->with($notification);
            }
        } else {
            $notification = ['message' => __('Something went wrong'), 'alert-type' => 'error'];
            return to_route('lawyer.zoom-meetings')->with($notification);
        }

    }
    public function getUserIdOrZero($meeting_id) {
        $userIds = MeetingHistory::where('meeting_id', $meeting_id)->pluck('user_id');

        return $userIds->count() === 1 ? $userIds->first() : 0;
    }
    public function update($id, Request $request) {
        $rules = [
            'topic'      => 'required',
            'start_time' => 'required',
            'duration'   => 'required|numeric',
            'client'    => 'required',
        ];

        $customMessages = [
            'topic.required'      => __('Topic is required'),
            'start_time.required' => __('Start time is required'),
            'duration.required'   => __('Duration is required'),
            'client.required'    => __('Client is required'),
        ];

        $this->validate($request, $rules, $customMessages);

        $zoom = new Zoom;
        $dataFormat = $zoom->dataFormat($request->topic, $request->duration, $request->start_time);
        $response = $zoom->updateMeeting($id, $dataFormat);
        if ($response['status'] == true) {
            $getMeeting = $zoom->getMeeting($id);
            $data = $getMeeting['data'];

            $lawyer = lawyerAuth();
            $start_time = date('Y-m-d H:i:s', strtotime($data['start_time']));
            $topic = $data['topic'];
            $duration = $data['duration'];
            $meeting_id = $data['id'];
            $password = $data['password'];
            $join_url = $data['join_url'];

            $meeting = ZoomMeeting::where('meeting_id', $meeting_id)->first();
            $meeting->start_time = $start_time;
            $meeting->topic = $topic;
            $meeting->duration = $duration;
            $meeting->meeting_id = $meeting_id;
            $meeting->password = $password;
            $meeting->join_url = $join_url;
            $meeting->save();

            if ($request->client == -1) {
                $appointments = Appointment::where('lawyer_id', $lawyer->id)->select('user_id')->groupBy('user_id')->get();
                $clientIds = $appointments->pluck('user_id');
                $users = User::whereIn('id', $clientIds)->get();

                foreach ($users as $user) {
                    $history = MeetingHistory::where('meeting_id', $meeting_id)->first();
                    $history->user_id = $user->id;
                    $history->meeting_id = $meeting_id;
                    $history->meeting_time = $start_time;
                    $history->duration = $duration;
                    $history->save();

                    // send email
                    try {
                        [$subject, $message] = $this->fetchEmailTemplate('zoom_meeting', ['client_name' => $user?->name, 'lawyer_name' => $lawyer?->name, 'meeting_schedule' => $start_time]);
                        $link = [__('Meeting Link') => $join_url];
                        $this->sendMail($user->email, $subject, $message, $link);
                    } catch (Exception $e) {
                        info($e->getMessage());
                    }
                }
            } else {
                $user = User::where('id', $request->client)->first();

                $history = MeetingHistory::where('meeting_id', $meeting_id)->first();
                $history->user_id = $user->id;
                $history->meeting_id = $meeting_id;
                $history->meeting_time = $start_time;
                $history->duration = $duration;
                $history->save();

                // send email
                try {
                    [$subject, $message] = $this->fetchEmailTemplate('zoom_meeting', ['client_name' => $user?->name, 'lawyer_name' => $lawyer?->name, 'meeting_schedule' => $start_time]);
                    $link = [__('Meeting Link') => $join_url];
                    $this->sendMail($user->email, $subject, $message, $link);
                } catch (Exception $e) {
                    info($e->getMessage());
                }
            }

            return $this->redirectWithMessage(RedirectType::CREATE->value, 'lawyer.zoom-meetings');

        }
        $notification = ['message' => __('Invalid Credentials') . ' ' . __('Please setup the credentials'), 'alert-type' => 'error'];
        return to_route('lawyer.zoom-credential')->with($notification);
    }

    public function destroy($id) {
        $meeting = ZoomMeeting::find($id);
        $meeting_id = $meeting->meeting_id;
        $zoom = new Zoom;
        $zoom->deleteMeeting($meeting_id);
        MeetingHistory::where('meeting_id', $meeting_id)->delete();
        ZoomMeeting::where('meeting_id', $meeting_id)->delete();
        $notification = ['message' => __('Deleted Successfully'), 'alert-type' => 'success'];
        return to_route('lawyer.zoom-meetings')->with($notification);
    }

    public function meetingHistory() {
        $lawyer = lawyerAuth();
        $now = now();
        $histories = MeetingHistory::where('lawyer_id', $lawyer->id)
            ->whereRaw('DATE_ADD(meeting_time, INTERVAL duration MINUTE) < ?', [$now])
            ->orderBy('meeting_time', 'asc')
            ->paginate(10);
        return view('lawyer.zoom.meeting.history', compact('histories'));
    }

    public function upCommingMeeting() {
        $lawyer = lawyerAuth();
        $now = now();
        $histories = MeetingHistory::where('lawyer_id', $lawyer->id)
            ->whereRaw('DATE_ADD(meeting_time, INTERVAL duration MINUTE) > ?', [$now])
            ->orderBy('meeting_time', 'asc')
            ->paginate(10);
        return view('lawyer.zoom.meeting.upcoming', compact('histories'));
    }
}
