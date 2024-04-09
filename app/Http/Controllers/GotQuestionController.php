<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class GotQuestionController extends Controller
{
    
    public function GotQuestion() : View
    {
        return view('got_questions.GotQuestion',[]);
    }

    public function GotQuestionAnswer() : View
    {
        return view('got_questions.GotQuestionAnswer',[]);
    }
 
}
