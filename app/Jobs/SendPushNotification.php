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

        Log::info("Job Started. Data: " . json_encode($this->pushData));
        try {
            $pusher = new NotificationPusher();
            $pusher->push($this->pushData);
            Log::info("Push Notification Sent Successfully.");
        } catch (\Exception $e) {
            Log::error("Job Failed: " . $e->getMessage());
        }
    }
}
