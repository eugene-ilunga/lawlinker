<?php

namespace Modules\Customer\app\Http\Controllers;

use App\Models\User;
use App\Enums\RedirectType;
use App\Models\UserDetails;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Traits\GlobalMailTrait;
use Illuminate\Support\Facades\DB;
use App\Services\MailSenderService;
use App\Traits\RedirectHelperTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use App\Traits\GetGlobalInformationTrait;
use Modules\Customer\app\Models\BannedHistory;

class CustomerController extends Controller {
    use GetGlobalInformationTrait, GlobalMailTrait, RedirectHelperTrait;

    public function index(Request $request) {
        checkAdminHasPermissionAndThrowException('client.view');

        $query = User::query();

        $query->when($request->filled('keyword'), function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->keyword . '%')
                ->orWhere('email', 'like', '%' . $request->keyword . '%');
        });

        $query->when($request->filled('verified'), function ($q) use ($request) {
            $q->where(function ($query) use ($request) {
                if ($request->verified == 1) {
                    $query->whereNotNull('email_verified_at');
                } elseif ($request->verified == 0) {
                    $query->whereNull('email_verified_at');
                }
            });
        });

        $query->when($request->filled('banned'), function ($q) use ($request) {
            $q->where(function ($query) use ($request) {
                if ($request->banned == 1) {
                    $query->where('is_banned', 'yes');
                } elseif ($request->banned == 0) {
                    $query->where('is_banned', 'no');
                }
            });
        });
        $orderBy = $request->filled('order_by') && $request->order_by == 1 ? 'asc' : 'desc';

        if ($request->filled('par-page')) {
            $users = $request->get('par-page') == 'all' ? $query->orderBy('id', $orderBy)->get() : $query->orderBy('id', $orderBy)->paginate($request->get('par-page'))->withQueryString();
        } else {
            $users = $query->orderBy('id', $orderBy)->paginate()->withQueryString();
        }

        return view('customer::all_customer')->with([
            'users' => $users,
        ]);
    }

    public function active_customer(Request $request) {
        checkAdminHasPermissionAndThrowException('client.view');

        $query = User::query();
        $query->where(['status' => 'active', 'is_banned' => 'no'])->where('email_verified_at', '!=', null);

        $query->when($request->filled('keyword'), function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->keyword . '%')
                ->orWhere('email', 'like', '%' . $request->keyword . '%');
        });

        $orderBy = $request->filled('order_by') && $request->order_by == 1 ? 'asc' : 'desc';

        if ($request->filled('par-page')) {
            $users = $request->get('par-page') == 'all' ? $query->orderBy('id', $orderBy)->get() : $query->orderBy('id', $orderBy)->paginate($request->get('par-page'))->withQueryString();
        } else {
            $users = $query->orderBy('id', $orderBy)->paginate()->withQueryString();
        }

        return view('customer::active_customer')->with([
            'users' => $users,
        ]);
    }

    public function non_verified_customers(Request $request) {
        checkAdminHasPermissionAndThrowException('client.view');

        $query = User::query();
        $query->where('email_verified_at', null);

        $query->when($request->filled('keyword'), function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->keyword . '%')
                ->orWhere('email', 'like', '%' . $request->keyword . '%');
        });
        $query->when($request->filled('banned'), function ($q) use ($request) {
            $q->where(function ($query) use ($request) {
                if ($request->banned == 1) {
                    $query->where('is_banned', 'yes');
                } elseif ($request->banned == 0) {
                    $query->where('is_banned', 'no');
                }
            });
        });
        $orderBy = $request->filled('order_by') && $request->order_by == 1 ? 'asc' : 'desc';

        if ($request->filled('par-page')) {
            $users = $request->get('par-page') == 'all' ? $query->orderBy('id', $orderBy)->get() : $query->orderBy('id', $orderBy)->paginate($request->get('par-page'))->withQueryString();
        } else {
            $users = $query->orderBy('id', $orderBy)->paginate()->withQueryString();
        }

        return view('customer::non_verified_customer')->with([
            'users' => $users,
        ]);
    }

    public function banned_customers(Request $request) {
        checkAdminHasPermissionAndThrowException('client.view');

        $query = User::query();
        $query->where('is_banned', 'yes');

        $query->when($request->filled('keyword'), function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->keyword . '%')
                ->orWhere('email', 'like', '%' . $request->keyword . '%');
        });

        $query->when($request->filled('verified'), function ($q) use ($request) {
            $q->where(function ($query) use ($request) {
                if ($request->verified == 1) {
                    $query->whereNotNull('email_verified_at');
                } elseif ($request->verified == 0) {
                    $query->whereNull('email_verified_at');
                }
            });
        });

        $orderBy = $request->filled('order_by') && $request->order_by == 1 ? 'asc' : 'desc';

        if ($request->filled('par-page')) {
            $users = $request->get('par-page') == 'all' ? $query->orderBy('id', $orderBy)->get() : $query->orderBy('id', $orderBy)->paginate($request->get('par-page'))->withQueryString();
        } else {
            $users = $query->orderBy('id', $orderBy)->paginate()->withQueryString();
        }

        return view('customer::banned_customer')->with([
            'users' => $users,
        ]);
    }

    public function show($id) {
        checkAdminHasPermissionAndThrowException('client.view');

        $user = User::findOrFail($id);

        $banned_histories = BannedHistory::where('user_id', $id)->orderBy('id', 'desc')->get();

        return view('customer::customer_show')->with([
            'user'             => $user,
            'banned_histories' => $banned_histories,
        ]);
    }

    public function update(Request $request, $id) {
        checkAdminHasPermissionAndThrowException('client.update');
        $user = User::findOrFail($id);

        $rules = [
            'name'       => 'required',
            'email'      => 'required|unique:users,email,' . $user->id,
            'phone'      => 'required',
            'age'        => 'required',
            'occupation' => 'required',
            'gender'     => 'required',
            'address'    => 'required',
            'country'    => 'required',
            'city'       => 'required',
        ];
        $customMessages = [
            'name.required'       => __('Name is required'),
            'email.required'      => __('Email is required'),
            'email.unique'        => __('Email already exist'),
            'phone.required'      => __('Phone is required'),
            'address.required'    => __('Address is required'),
            'age.required'        => __('age is required'),
            'occupation.required' => __('Occupation is required'),
            'gender.required'     => __('Gender is required'),
            'country.required'    => __('Country is required'),
            'city.required'       => __('City is required'),
        ];
        $request->validate($rules, $customMessages);
        try {
            DB::beginTransaction();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->ready_for_appointment = 1;

            $user->save();

            UserDetails::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'phone'                   => $request->phone,
                    'guardian_name'           => $request->guardian_name,
                    'guardian_phone'          => $request->guardian_phone,
                    'age'                     => $request->age,
                    'occupation'              => $request->occupation,
                    'gender'                  => $request->gender,
                    'address'                 => $request->address,
                    'country'                 => $request->country,
                    'city'                    => $request->city,
                    'date_of_birth'           => $request->date_of_birth,
                ]);
            DB::commit();

            $notification = __('Updated successfully');
            $notification = ['message' => $notification, 'alert-type' => 'success'];

            return redirect()->back()->with($notification);
        } catch (\Exception $e) {
            DB::rollBack();
            info($e->getMessage());
            $notification = __('Update failed');
            $notification = ['message' => $notification, 'alert-type' => 'error'];

            return redirect()->back()->with($notification);
        }
    }

    public function password_change(Request $request, $id) {
        checkAdminHasPermissionAndThrowException('client.update');

        $rules = [
            'password' => 'required|min:4|confirmed',
        ];
        $customMessages = [
            'password.required'  => __('Password is required'),
            'password.min'       => __('Password minimum 4 character'),
            'password.confirmed' => __('Confirm password does not match'),
        ];
        $this->validate($request, $rules, $customMessages);

        $user = User::findOrFail($id);

        $user->password = Hash::make($request->password);
        $user->save();

        $notification = __('Password change successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }

    public function send_banned_request(Request $request, $id) {
        checkAdminHasPermissionAndThrowException('client.update');

        $rules = [
            'subject'     => 'required|max:255',
            'description' => 'required',
        ];
        $customMessages = [
            'subject.required'     => __('Subject is required'),
            'description.required' => __('Description is required'),
        ];

        $this->validate($request, $rules, $customMessages);

        $user = User::findOrFail($id);
        if ($user->is_banned == 'yes') {
            $user->is_banned = 'no';
            $user->save();

            $banned = new BannedHistory();
            $banned->user_id = $id;
            $banned->subject = $request->subject;
            $banned->reasone = 'for_unbanned';
            $banned->description = $request->description;
            $banned->save();
        } else {
            $user->is_banned = 'yes';
            $user->save();

            $banned = new BannedHistory();
            $banned->user_id = $id;
            $banned->subject = $request->subject;
            $banned->reasone = 'for_banned';
            $banned->description = $request->description;
            $banned->save();
        }

        //Mail send
        $this->sendMail($user->email, $request->subject, $request->description);

        $notification = __('Banned request successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);

    }

    public function send_verify_request(Request $request, $id) {

        $user = User::findOrFail($id);
        $user->verification_token = Str::random(100);
        $user->save();
        try {
            (new MailSenderService)->sendVerifyMailSingleUser($user);
        } catch (\Exception $e) {
            info($e->getMessage());
        }


        $notification = __('A verification link has been send to user mail');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);

    }

    public function send_verify_request_to_all(Request $request) {

        try {
            (new MailSenderService)->sendVerifyMailToAllUser();
        } catch (\Exception $e) {
            info($e->getMessage());
        }

        $notification = __('A verification link has been send to user mail');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);

    }

    public function send_mail_to_customer(Request $request, $id) {
        $rules = [
            'subject'     => 'required|max:255',
            'description' => 'required',
        ];
        $customMessages = [
            'subject.required'     => __('Subject is required'),
            'description.required' => __('Description is required'),
        ];

        $this->validate($request, $rules, $customMessages);

        $user = User::select('email')->findOrFail($id);

        //send mail
        try {
            $this->sendMail($user->email, $request->subject, $request->description);
        } catch (\Exception $e) {
            info($e->getMessage());
        }

        $notification = __('Mail sent to client successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }

    public function send_bulk_mail() {
        checkAdminHasPermissionAndThrowException('client.bulk.mail');
        return view('customer::send_bulk_mail');
    }

    public function send_bulk_mail_to_all(Request $request) {
        checkAdminHasPermissionAndThrowException('client.bulk.mail');

        $rules = [
            'subject'     => 'required|max:255',
            'description' => 'required',
        ];

        $customMessages = [
            'subject.required'     => __('Subject is required'),
            'description.required' => __('Description is required'),
        ];

        $this->validate($request, $rules, $customMessages);

        $userCount = User::select('id')->where(['status' => 'active', 'is_banned' => 'no'])->where('email_verified_at', '!=', null)->count();

        if ($userCount > 0) {
            $email_list = User::select('email')->where(['status' => 'active', 'is_banned' => 'no'])->where('email_verified_at', '!=', null)->orderBy('id', 'desc')->get();

            try {
                (new MailSenderService)->SendBulkEmail($email_list, $request->subject, $request->description);
            } catch (\Exception $e) {
                info($e->getMessage());
            }

            $notification = __('Mail sent to client successfully');
            $notification = ['message' => $notification, 'alert-type' => 'success'];
        } else {
            $notification = __('Mail can not be sent because no active user was found.');
            $notification = ['message' => $notification, 'alert-type' => 'error'];
        }

        return redirect()->back()->with($notification);

    }

    public function destroy($id) {
        checkAdminHasPermissionAndThrowException('client.delete');

        $user = User::findOrFail($id);
        if ($user->appointments()->count() > 0) {
            return $this->redirectWithMessage(RedirectType::ERROR->value);
        }
        if ($user->image) {
            if (File::exists(public_path($user->image))) {
                unlink(public_path($user->image));
            }
        }
        $user->meeting_history()->delete();
        $user->socialite()->delete();
        $user->details()->delete();
        $user->delete();

        $notification = __('Client deleted successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->route('admin.all-customers')->with($notification);
    }
}
