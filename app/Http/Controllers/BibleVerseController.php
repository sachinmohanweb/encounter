<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class BibleVerseController extends Controller
{
    
    public function DailyBibleVerse() : View
    {
        return view('bible_verse.DailyBibleVerse',[]);
    }

    public function AddDailyBibleVerse() : View
    {
        return view('bible_verse.AddDailyBibleVerse',[]);
    }
    public function EditDailyBibleVerse() : View
    {
        return view('bible_verse.EditDailyBibleVerse',[]);

    }
 
}
