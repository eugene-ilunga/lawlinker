<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Modules\NewsLetter\app\Models\NewsLetter;

class InActiveSubscriber extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inactive:subscriber';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete Automatically Inactive subscriber Everyday';

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
        $subscribers = NewsLetter::where('status', 'not_verified')->get();
        foreach ($subscribers as $subscriber) {
            NewsLetter::destroy($subscriber->id);
        }
    }
}
