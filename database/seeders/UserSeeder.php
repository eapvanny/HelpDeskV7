<?php

namespace Database\Seeders;

use App\Models\Status;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure the role exists before assigning it
        if (Role::where('name', 'Admin')->doesntExist()) {
            $adminRole = Role::create(['name' => 'Admin']);
        } else {
            $adminRole = Role::where('name', 'Admin')->first();
        }

        if (User::where('email', 'admin@gmail.com')->doesntExist()) {
            $user = User::create([
                'name' => 'Admin',
                'username' => 'Vanny_Admin',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('admin123'),
                'photo' => '123.jpg',
                'status' => 1,
                'role_id' => 1,
                'department_id' => 1,
                'phone_no' => '0987876567',
            ]);

            // Assign role using Spatie
            $user->syncRoles($adminRole->name);

            // Ensure the user role record is created
            UserRole::create([
                'user_id' => $user->id,
                'role_id' => $adminRole->id,
            ]);
        }
    }
}
