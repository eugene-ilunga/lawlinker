<?php

namespace App\Console\Commands;

use App\Traits\GlobalMailTrait;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Modules\Appointment\app\Models\Appointment;

class PreNotification extends Command {
    use GlobalMailTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'prenotification:appointment';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Appointment Pre-Notification';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle() {
        $appointments = Appointment::notTreated()->get();
        $setting = cache()->get('setting');

        foreach ($appointments as $appointment) {
            $schedule = $appointment->schedule->start_time . '-' . $appointment->schedule->end_time;
            $appointment_time = strtotime($appointment->date . $appointment->schedule->start_time);
            $pre_notify_time = $appointment_time - $setting->prenotification_hour * 3600;
            $current_time = Carbon::now()->timestamp;

            if ($pre_notify_time == $current_time) {
                try {
                    [$subject, $message] = $this->fetchEmailTemplate('pre_notification', ['client_name' => $appointment->user->name, 'schedule' => $schedule, 'date' => $appointment->date, 'lawyer_name' => $appointment->lawyer->name]);
                    $this->sendMail($appointment->user->email, $subject, $message);
                } catch (\Exception $e) {
                    info($e->getMessage());
                }
            }
        }
    }
}
