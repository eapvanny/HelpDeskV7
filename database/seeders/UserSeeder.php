<?php

namespace Database\Seeders;

use App\Models\Status;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (User::count() === 0) {
            $user = new User();
            $user->name = 'Admin';
            $user->username = 'Vanny_Admin';
            $user->email = 'admin@gmail.com';
            $user->password = Hash::make('admin123');
            $user->photo = '123.jpg';
            $user->status = 1;
            $user->department_id = 1;
            $user->phone_no = '0987876567';
            $user->save();
        }
    }
}
