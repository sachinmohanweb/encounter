<?php

namespace App\Http\Repositories;

use App\Models\User;

class UserRepository {

    function __construct() {
        
    }

    function checkUser($data){

        $user=User::where('email',$data['email'])->where('status',1)->first();
        
        if($user)
        {
            return $user;

        }else{

            return '';
        }   
    }
}