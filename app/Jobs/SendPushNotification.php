<?php

namespace App\Jobs;

use App\Notifications\NotificationPusher;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Log;

class SendPushNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $pushData;

    /**
     * Create a new job instance.
     */
    public function __construct(array $pushData)
    {
        $this->pushData = $pushData;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {

        Log::channel('notification_log')->info("======>>>>>Notifications Job Call ".$this->pushData."  ======>>>>>\n");
        $pusher = new NotificationPusher();
        $pusher->push($this->pushData);
    }
}
