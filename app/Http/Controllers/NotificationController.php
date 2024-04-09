<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationController extends Controller
{
    
    public function Notifications() : View
    {
        return view('notifications.Notification',[]);
    }

    public function AddNotification() : View
    {
        return view('notifications.AddNotification',[]);
    }

    public function EditNotification() : View
    {
        return view('notifications.EditNotification',[]);

    }
}
