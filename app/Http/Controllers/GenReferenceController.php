<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class GenReferenceController extends Controller
{
    
    public function GereralReference() : View
    {
        return view('general_references.GeneralReference',[]);
    }

    public function AddGereralReference() : View
    {
        return view('general_references.AddGeneralReference',[]);
    }

    public function EditGereralReference() : View
    {
        return view('general_references.EditGeneralReference',[]);

    }
}
