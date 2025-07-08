<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\FailJobTest;

class TestJobController extends Controller
{
    public function triggerFailingJob()
    {
        dispatch(new FailJobTest())->onQueue('push-bulk-notifications');

        return response()->json([
            'message' => 'FailJobTest dispatched (will fail)',
        ]);
    }
}
