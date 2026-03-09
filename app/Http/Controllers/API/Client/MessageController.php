<?php

namespace App\Http\Controllers\API\Client;

use App\Events\LawyerChatMessage;
use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\Lawyer\app\Models\Lawyer;
use Modules\GlobalSetting\app\Models\Setting;

class MessageController extends Controller {
    public function index(): JsonResponse {
        $userId = auth()->guard('api')->user()->id;

        $lawyers = Lawyer::withCount(['messages as unread_messages_count' => function ($query) use ($userId) {
            $query->where('user_view', 0)->where('user_id', $userId);
        }])
        ->whereHas('appointments', function ($query) use ($userId) {
            $query->where('user_id', $userId)->paymentSuccess();
        })->get()->map(function ($lawyer) {
            $lawyer->latest_message = $lawyer->messages()->select('message','send_lawyer','send_user')->latest()->first();
            return $lawyer;
        });

        if ($lawyers->count()) {
            return response()->json(['status' => 'success', 'appointed_lawyers' => $lawyers], 200);
        }
        return response()->json(['status' => 'error', 'message' => 'Not Found!'], 404);
    }

    public function getMessage($lawyer_id): JsonResponse {
        $user = auth()->guard('api')->user();
        Message::where(['user_id' => $user->id, 'lawyer_id' => $lawyer_id])->update(['user_view' => 1]);
        $messages = Message::where(['user_id' => $user->id, 'lawyer_id' => $lawyer_id])->get();

        $unseen_message = Message::where(['user_id' => $user->id, 'user_view' => 0])->whereNot('lawyer_id', $lawyer_id)->count();

        if ($messages->count()) {
            return response()->json(['status' => 'success', 'messages' => $messages, 'unseen_message' => $unseen_message], 200);
        }
        return response()->json(['status' => 'error', 'message' => 'Not Found!'], 404);
    }
    public function seenMessage($lawyer_id): JsonResponse {
        $my_id = auth()->guard('api')->user()?->id;
        Message::where(['user_id' => $my_id, 'lawyer_id' => $lawyer_id])->update(['user_view' => 1]);
        return response()->json(['status' => 'success', 'message' => 'All messages from this lawyer are seen'], 200);
    }

    public function sendMessage(Request $request): JsonResponse {
        $validator = Validator::make($request->all(), [
            'receiver_id' => 'required',
            'message'     => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => $validator->errors()], 422);
        }

        $user = auth()->guard('api')->user();

        // Save message to the database
        $message = new Message();
        $message->lawyer_id = $request->receiver_id;
        $message->user_id = $user->id;
        $message->message = $request->message;
        $message->send_user = true;
        $message->save();

        // Broadcast the event
        $data = (object) [
            'message'     => $message->message,
            'sender_id' => $user->id,
            'receiver_id' => $message->lawyer_id,
            'created_at'  => formattedDateTime($message->created_at),
            'un_seen'     => Message::where([
                'user_id'     => $message->user_id,
                'lawyer_id'   => $message->lawyer_id,
                'lawyer_view' => 0,
            ])->count(),
        ];
        $pusher_status = Setting::where('key', 'pusher_status')->value('value');
        if ($pusher_status == 'active') {
            event(new LawyerChatMessage($data));
        }

        return response()->json(['status' => 'success', 'message' => Message::where('id', $message?->id)->first()], 200);
    }
}
