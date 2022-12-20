<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => 'Test Admin',
            'email' => 'test@email.com',
            'password' => Hash::make(env('TEST_USER_PASSWORD'))
        ]);

        $user->assignRole('admin');

        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@user.com',
            'password' => Hash::make(env('TEST_USER_PASSWORD'))
        ]);

        $user->assignRole('user');
    }
}
