<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;

class AdminSeeder extends Seeder
{

    public function run()
    {
        // $user = [
        //     'name'  => 'User',
        //     'email' => 'user@user.com',
        //     'password' => bcrypt('password')
        // ];
        // User::create($user);

        $admin = [
            ['name'  => 'Admin','email' => 'admin@encounter.com','password' =>bcrypt('encounter@123')]
        ];

        Admin::insert($admin);
    }
}
