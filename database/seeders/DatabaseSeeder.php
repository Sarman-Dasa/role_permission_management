<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

       $user = User::create([
            'first_name'        => 'Super',
            'last_name'         => 'Admin',
            'email'             => 'sadmin@example.com',
            'phone'             => '1234567890',
            'email_verified_at' =>  now(),
            'password'          =>  Hash::make(12345678),
            'is_active'         => true,
        ]);

        $user->roles()->attach(1);
    }
}
