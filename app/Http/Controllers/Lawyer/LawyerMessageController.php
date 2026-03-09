<?php

namespace App\Http\Controllers\Lawyer;

use App\Models\User;
use App\Models\Message;
use Illuminate\Http\Request;
use App\Events\ClientChatMessage;
use App\Http\Controllers\Controller;
use Modules\GlobalSetting\app\Models\Setting;
use Modules\Appointment\app\Models\Appointment;

class LawyerMessageController extends Controller {
    public function index() {
        $users = Appointment::with('user')->where('lawyer_id', lawyerAuth()?->id)->groupBy('user_id')->select('user_id')->get();
        return view('lawyer.message.index', compact('users'));
    }

    public function messageBox($id) {
        $user = User::find($id);
        $user_id = $user->id;
        $my_id = lawyerAuth()?->id;
        Message::where(['lawyer_id' => $my_id, 'user_id' => $user_id])->update(['lawyer_view' => 1]);
        $messages = Message::where(['lawyer_id' => $my_id, 'user_id' => $user_id])->get();
        $users = Appointment::with('user')->where('lawyer_id', $my_id)->groupBy('user_id')->select('user_id')->get();
        return view('lawyer.message.single-message', compact('users', 'messages', 'user_id'));

    }

    public function getMessage($user_id) {
        $my_id = lawyerAuth()?->id;
        Message::where(['lawyer_id' => $my_id, 'user_id' => $user_id])->update(['lawyer_view' => 1]);
        $messages = Message::where(['lawyer_id' => $my_id, 'user_id' => $user_id])->get();
        return view('lawyer.message.message-box', compact('messages'));
    }
    public function seenMessage($user_id) {
        $my_id = lawyerAuth()?->id;
        Message::where(['lawyer_id' => $my_id, 'user_id' => $user_id])->update(['lawyer_view' => 1]);
        return response()->json(['status' => 'success'], 200);
    }

    public function sendMessage(Request $request) {
        $this->validate($request, [
            'receiver_id' => 'required',
            'message'     => 'required',
        ]);
        $my_id = lawyerAuth()?->id;

        // Save message to the database
        $message = new Message();
        $message->lawyer_id = $my_id;
        $message->user_id = $request->receiver_id;
        $message->message = strip_tags($request->message);
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
        $pusher_status = cache('setting')?->pusher_status ?? Setting::where('key', 'pusher_status')->value('value');
        if($pusher_status == 'active'){
            event(new ClientChatMessage($data));
        }

        return response()->json(['user_id' => $request->receiver_id]);

    }
}
