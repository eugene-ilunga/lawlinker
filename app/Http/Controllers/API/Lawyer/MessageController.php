<?php

namespace App\Http\Controllers\API\Lawyer;

use App\Models\User;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Events\ClientChatMessage;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Modules\GlobalSetting\app\Models\Setting;

class MessageController extends Controller
{
    public function index(): JsonResponse {
        $lawyerId = auth()->guard('lawyer_api')->user()->id;

        $users = User::withCount(['messages as unread_messages_count' => function ($query) use ($lawyerId) {
            $query->where('lawyer_view', 0)->where('lawyer_id', $lawyerId);
        }])
        ->whereHas('appointments', function ($query) use ($lawyerId) {
            $query->where('lawyer_id', $lawyerId)->paymentSuccess();
        })->get()->map(function ($user) {
            $user->latest_message = $user->messages()->select('message','send_lawyer','send_user')->latest()->first();
            return $user;
        });

        if ($users->count()) {
            return response()->json(['status' => 'success', 'appointed_clients' => $users], 200);
        }
        return response()->json(['status' => 'error', 'message' => 'Not Found!'], 404);
    }


    public function getMessage($client_id): JsonResponse {
        $my_id = auth()->guard('lawyer_api')->user()?->id;
        Message::where(['lawyer_id' => $my_id, 'user_id' => $client_id])->update(['lawyer_view' => 1]);
        $messages = Message::where(['lawyer_id' => $my_id, 'user_id' => $client_id])->get();

        if ($messages->count()) {
            return response()->json(['status' => 'success', 'messages' => $messages], 200);
        }
        return response()->json(['status' => 'error', 'message' => 'Not Found!'], 404);
    }

    public function sendMessage(Request $request): JsonResponse {
        $validator = Validator::make($request->all(), [
            'receiver_id' => 'required',
            'message'     => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => $validator->errors()], 422);
        }
        $my_id = auth()->guard('lawyer_api')->user()?->id;

        // Save message to the database
        $message = new Message();
        $message->lawyer_id = $my_id;
        $message->user_id = $request->receiver_id;
        $message->message = $request->message;
        $message->send_lawyer = true;
        $message->save();

        // Broadcast the event
        $data = (object) [
            'message' => $message->message,
            'sender_id' => $my_id,
            'receiver_id' => $message->user_id,
            'created_at' => formattedDateTime($message->created_at),
            'un_seen'     => Message::where([
                'user_id' => $message->user_id,
                'lawyer_id' => $message->lawyer_id,
                'user_view' => 0,
            ])->count(),
        ];
        $pusher_status = Setting::where('key', 'pusher_status')->value('value');
        if($pusher_status == 'active'){
            event(new ClientChatMessage($data));
        }

        return response()->json(['status' => 'success','message'=> Message::where('id',$message?->id)->first()], 200);
    }
    public function seenMessage($client_id): JsonResponse {
        $my_id = auth()->guard('lawyer_api')->user()?->id;
        Message::where(['lawyer_id' => $my_id, 'user_id' => $client_id])->update(['lawyer_view' => 1]);
        return response()->json(['status' => 'success','message'=>'All messages from this client are seen'], 200);
    }
}
