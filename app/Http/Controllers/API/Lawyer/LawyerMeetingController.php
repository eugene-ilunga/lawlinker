<?php

namespace App\Http\Controllers\API\Lawyer;

use App\Http\Controllers\Controller;
use App\Models\MeetingHistory;
use App\Models\User;
use App\Models\ZoomMeeting;
use App\Services\Zoom;
use App\Traits\GlobalMailTrait;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\Appointment\app\Models\Appointment;

class LawyerMeetingController extends Controller {
    use GlobalMailTrait;

    public function index(): JsonResponse {
        $lawyer = auth()->guard('lawyer_api')->user();
        $meetings = ZoomMeeting::where('lawyer_id', $lawyer->id)->orderBy('start_time', 'desc')->paginate(10);
        if ($meetings) {
            return response()->json(['status' => 'success', 'data' => $meetings], 200);
        }
        return response()->json(['status' => 'error', 'message' => 'Not Found!'], 404);
    }
    public function store(Request $request): JsonResponse {
        $validator = Validator::make($request->all(), [
            'topic'      => 'required',
            'client'    => 'required',
            'start_time' => 'required',
            'duration'   => 'required|numeric',
        ], [
            'topic.required'      => __('Topic is required'),
            'client.required'    => __('Client is required'),
            'start_time.required' => __('Start time is required'),
            'duration.required'   => __('Duration is required'),

        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => $validator->errors()], 422);
        }
        $zoom = new Zoom;
        $dataFormat = $zoom->dataFormat($request->topic, $request->duration, $request->start_time);
        $response = $zoom->createMeeting($dataFormat);
        if ($response['status'] == true) {
            $data = $response['data'];

            $lawyer = auth()->guard('lawyer_api')->user();
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
                if ($user) {
                    $meeting_link = $request->join_url;

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
                } else {
                    return response()->json(['status' => 'error', 'message' => __('Client Not Found')], 404);
                }
            }
            return response()->json(['status' => 'success', 'message' => __('Created Successfully')], 201);

        }
        return response()->json(['status' => 'success', 'message' => __('Invalid Credentials') . ' ' . __('Please setup the credentials')], 400);

    }

    public function edit($id): JsonResponse {
        $lawyer = auth()->guard('lawyer_api')->user();
        $meeting = ZoomMeeting::where('meeting_id', $id)->where('lawyer_id', $lawyer->id)->first();
        if ($meeting) {
            return response()->json(['status' => 'success', 'data' => $meeting], 200);
        } else {
            return response()->json(['status' => 'error', 'message' => __('Not Found!')], 404);
        }

    }
    public function users(): JsonResponse {
        $lawyer = auth()->guard('lawyer_api')->user();
        $clients = Appointment::where('lawyer_id', $lawyer->id)->select('user_id')->groupBy('user_id')->get();
        $clientIds = $clients->pluck('user_id');

        $users = User::whereIn('id', $clientIds)->get();
        if ($users) {
            return response()->json(['status' => 'success', 'data' => $users], 200);

        } else {
            return response()->json(['status' => 'error', 'message' => __('Not Found!')], 404);
        }

    }
    public function update($id, Request $request): JsonResponse {
        $validator = Validator::make($request->all(), [
            'topic'      => 'required',
            'client'    => 'required',
            'start_time' => 'required',
            'duration'   => 'required|numeric',
        ], [
            'topic.required'      => __('Topic is required'),
            'client.required'    => __('Client is required'),
            'start_time.required' => __('Start time is required'),
            'duration.required'   => __('Duration is required'),

        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => $validator->errors()], 422);
        }
        $lawyer = auth()->guard('lawyer_api')->user();
        $meeting = ZoomMeeting::where('meeting_id', $id)->where('lawyer_id', $lawyer->id)->first();
        if (!$meeting) {
            return response()->json(['status' => 'error', 'message' => __('Not Found!')], 404);
        }
        $old_meeting_id = $meeting->meeting_id;

        $zoom = new Zoom;
        $dataFormat = $zoom->dataFormat($request->topic, $request->duration, $request->start_time);
        $response = $zoom->updateMeeting($old_meeting_id, $dataFormat);
        if ($response['status'] == true) {
            $getMeeting = $zoom->getMeeting($old_meeting_id);
            $data = $getMeeting['data'];
            $start_time = date('Y-m-d H:i:s', strtotime($data['start_time']));
            $topic = $data['topic'];
            $duration = $data['duration'];
            $meeting_id = $data['id'];
            $password = $data['password'];
            $join_url = $data['join_url'];

            $meeting->lawyer_id = $lawyer->id;
            $meeting->start_time = date('Y-m-d H:i:s', strtotime($request->start_time));
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
                    $history = MeetingHistory::where('meeting_id', $old_meeting_id)->first();
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
                if ($user) {
                    $history = MeetingHistory::where('meeting_id', $old_meeting_id)->first();
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
                } else {
                    return response()->json(['status' => 'error', 'message' => __('Client Not Found')], 404);
                }

            }

            return response()->json(['status' => 'success', 'message' => __('Updated successfully')], 200);
        }
        return response()->json(['status' => 'success', 'message' => __('Invalid Credentials') . ' ' . __('Please setup the credentials')], 400);
    }
    public function destroy($id): JsonResponse {
        $lawyer = auth()->guard('lawyer_api')->user();
        $meeting = ZoomMeeting::where('meeting_id', $id)->where('lawyer_id', $lawyer->id)->first();
        if (!$meeting) {
            return response()->json(['status' => 'error', 'message' => __('Not Found!')], 404);
        }
        $meeting_id = $meeting->meeting_id;
        $zoom = new Zoom;
        $zoom->deleteMeeting($meeting_id);
        MeetingHistory::where('meeting_id', $meeting->meeting_id)->delete();
        ZoomMeeting::where('meeting_id', $meeting_id)->delete();
        return response()->json(['status' => 'success', 'message' => __('Deleted Successfully')], 200);
    }
    public function meetingHistory(): JsonResponse {
        $lawyer = auth()->guard('lawyer_api')->user();
        $now = now();
        $histories = MeetingHistory::select('id', 'user_id', 'meeting_id')->with(['user:id,name,email,image', 'meeting:topic,duration,start_time,meeting_id,password,join_url'])->where('lawyer_id', $lawyer->id)
            ->whereRaw('DATE_ADD(meeting_time, INTERVAL duration MINUTE) < ?', [$now])
            ->orderBy('meeting_time', 'asc')
            ->paginate(10);
        if ($histories) {
            return response()->json(['status' => 'success', 'data' => $histories], 200);
        }
        return response()->json(['status' => 'error', 'message' => 'Not Found!'], 404);

    }

    public function upComingMeeting(): JsonResponse {
        $lawyer = auth()->guard('lawyer_api')->user();
        $now = now();
        $histories = MeetingHistory::select('id', 'user_id', 'meeting_id')->with(['user:id,name,email,image', 'meeting:topic,duration,start_time,meeting_id,password,join_url'])->where('lawyer_id', $lawyer->id)
            ->whereRaw('DATE_ADD(meeting_time, INTERVAL duration MINUTE) > ?', [$now])
            ->orderBy('meeting_time', 'asc')
            ->paginate(10);
        if ($histories) {
            return response()->json(['status' => 'success', 'data' => $histories], 200);
        }
        return response()->json(['status' => 'error', 'message' => 'Not Found!'], 404);

    }
}
