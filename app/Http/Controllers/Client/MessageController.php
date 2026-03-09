<?php

namespace App\Http\Controllers\Client;

use App\Models\Message;
use Illuminate\Http\Request;
use App\Events\LawyerChatMessage;
use App\Http\Controllers\Controller;
use Modules\Lawyer\app\Models\Lawyer;
use Modules\GlobalSetting\app\Models\Setting;
use Modules\Appointment\app\Models\Appointment;

class MessageController extends Controller {
    public function index() {
        $user = userAuth();
        $lawyers = Appointment::with('lawyer')->where('user_id', $user->id)->groupBy('lawyer_id')->select('lawyer_id')->get();
        return view('client.profile.message.index', compact('user', 'lawyers'));
    }

    public function messageBox($slug) {
        $lawyer = Lawyer::where('slug', $slug)->first();
        $lawyer_id = $lawyer->id;
        $user = userAuth();
        Message::where(['user_id' => $user->id, 'lawyer_id' => $lawyer_id])->update(['user_view' => 1]);
        $messages = Message::where(['user_id' => $user->id, 'lawyer_id' => $lawyer_id])->get();

        $lawyers = Appointment::with('lawyer')->where('user_id', $user->id)->groupBy('lawyer_id')->select('lawyer_id')->get();
        return view('client.profile.message.single-message', compact('messages', 'lawyers', 'user', 'lawyer_id'));
    }

    public function getMessage($lawyer_id) {
        $user = userAuth();
        $my_id = $user->id;
        Message::where(['user_id' => $user->id, 'lawyer_id' => $lawyer_id])->update(['user_view' => 1]);
        $messages = Message::where(['user_id' => $user->id, 'lawyer_id' => $lawyer_id])->get();

        $view = view('client.profile.message.message-box', compact('messages'))->render();
        $unseen_message = Message::where(['user_id' => $user->id, 'user_view' => 0])->whereNot('lawyer_id', $lawyer_id)->count();

        // Return a JSON response
        return response()->json([
            'view'           => $view,
            'unseen_message' => $unseen_message,
        ]);
    }
    public function seenMessage($lawyer_id) {
        $my_id = userAuth()?->id;
        Message::where(['user_id' => $my_id, 'lawyer_id' => $lawyer_id])->update(['user_view' => 1]);
        return response()->json(['status' => 'success'], 200);
    }

    public function sendMessage(Request $request) {
        $this->validate($request, [
            'receiver_id' => 'required',
            'message'     => 'required',
        ]);

        $user = userAuth();

        // Save message to the database
        $message = new Message();
        $message->lawyer_id = $request->receiver_id;
        $message->user_id = $user->id;
        $message->message = strip_tags($request->message);
        $message->send_user = true;
        $message->save();

        // Broadcast the event
        $data = (object) [
            'message'     => $message->message,
            'sender_id' => $user->id,
            'receiver_id' => $message->lawyer_id,
            'created_at'  => formattedDateTime($message->created_at),
            'un_seen'     => Message::where([
                'user_id' => $message->user_id,
                'lawyer_id' => $message->lawyer_id,
                'lawyer_view' => 0,
            ])->count(),
        ];
        $pusher_status = cache('setting')?->pusher_status ?? Setting::where('key', 'pusher_status')->value('value');
        if($pusher_status == 'active'){
            event(new LawyerChatMessage($data));
        }

        return response()->json(['lawyer_id' => $request->receiver_id]);
    }
}
