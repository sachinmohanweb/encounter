<?php
namespace App\Notifications;

use Kreait\Firebase\Messaging\Notification;
use Kreait\Firebase\Messaging\AndroidConfig;
use Kreait\Firebase\Messaging\ApnsConfig;
use Kreait\Firebase\Messaging\WebPushConfig;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Laravel\Firebase\Facades\Firebase;
use Kreait\Firebase\Messaging\MulticastMessage;


use Illuminate\Support\Facades\Log; 

class NotificationPusher
{
    
    public function __construct()
    {   
        
    }

    public function push($msg)
    {

        $deviceToken = $msg['tokens'];
        $title       = $msg['title'];
        $body        = $msg['body'];

        $route       = $msg['route'];
        $id          = $msg['id'];
        $category    = $msg['category'];
        $data1       = $msg['data1'];
        $data2       = $msg['data2'];
        $data3       = $msg['data3'];
        $data4       = $msg['data4'];
        $data5       = $msg['data5'];
        $image1      = $msg['image1'];

        $imageUrl = "https://encounterbiblestudy.com/public/assets/images/logo.png";
        
        Log::info('data: ', $msg['tokens']);

        $AndroidConfig = AndroidConfig::fromArray([
                            'ttl' => '3600s',
                            'priority' => 'normal',
                            'notification' => [
                                'title' => $title,
                                'body'  => $body,
                                'color' => '#2c5acf',
                            ],
                            'data' => [
                                'click_action'      => $route,
                                'table_id'          => (string) $id,
                                'category'          => $category,
                                'data1'             => $data1,
                                'data2'             => $data2,
                                'data3'             => $data3,
                                'data4'             => $data4,
                                'data5'             => $data5,
                                'image1'            => $image1,
                            ]
                        ]);

        $ApnsConfig = ApnsConfig::fromArray([
                        'headers' => [
                            'apns-priority' => '10',
                        ],
                        'payload' => [
                            'aps' => [
                                'alert' => [
                                    'title' => $title,
                                    'body' => $body,
                                ],
                                'badge' => 42,
                                'sound' => 'default',
                            ],

                            'image_url' => $imageUrl,

                            'table_id' => (string) $id,

                            'custom_data' => [
                                'action_route' => $route
                            ],
                        ],
                    ]);

        $webconfig = WebPushConfig::fromArray([
            'notification' => [
                'title' => $title,
                'body' => $body,
                'icon' => $imageUrl,
            ],
            'fcm_options' => [
                'link' => '',
            ],
        ]);

        $message = CloudMessage::new()
            ->withAndroidConfig($AndroidConfig)
            ->withApnsConfig($ApnsConfig)
            ->withWebPushConfig($webconfig)
            ->withData(['image' => $imageUrl]);


        $response = Firebase::messaging()->sendMulticast($message,$deviceToken);

        Log::info('FCM Multicast Response', [
            'type' => $route,
            'successCount' => $response->successes()->count(),
            'failureCount' => $response->failures()->count(),
            'failedTokens' => $response->failures()->map(function ($failure) {
                return [
                    'token' => $failure->target()->value(),
                    'error' => $failure->error()->getMessage(),
                ];
            }),
        ]);

        return true;
        
    }
}