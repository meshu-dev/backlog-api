<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        $password = env('USER_PASSWORD', 'testtest');
        $password = bcrypt($password);

        User::factory()->create([
            'name' => env('USER_NAME', 'Test user'),
            'email' => env('USER_EMAIL', 'example@mail.com'),
            'password' => $password
        ]);
    }
}
