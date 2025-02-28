<?php

namespace Database\Seeders;

use App\Http\Helpers\AppHelper;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create all roles defined in AppHelper
        $roles = [];
        foreach (AppHelper::USER as $roleId => $roleName) {
            $roles[$roleId] = Role::firstOrCreate(['name' => $roleName]);
        }

        // Ensure the super admin user exists
        if (User::where('email', 'superadmin@gmail.com')->doesntExist()) {
            $user = User::create([
                'name' => 'Mr. Admin',
                'username' => 'superadmin',
                'email' => 'superadmin@gmail.com',
                'password' => Hash::make('demo123'),
                // 'photo' => '123.jpg',
                'staff_id_card' => '8332',
                'position' => 'Developer',
                'gender' => AppHelper::GENDER_MALE,
                'status' => AppHelper::STATUS_OPEN,
                'role_id' => AppHelper::USER_SUPER_ADMIN, // Assign correct role_id
                'phone_no' => '0987876567',
            ]);

            // Assign the Super Admin role using Spatie
            $user->syncRoles($roles[AppHelper::USER_SUPER_ADMIN]->name);

            // Store user role mapping
            UserRole::create([
                'user_id' => $user->id,
                'role_id' => $roles[AppHelper::USER_SUPER_ADMIN]->id,
            ]);
        }

        // Define permissions
        $permissions = [
            'view department',
            'create department',
            'update department',
            'delete department',
            'create ticket',
            'view ticket',
            'update ticket',
            'delete ticket',
            'view status',
            'view priority',
            'create user',
            'view user',
            'update user',
            'delete user',
            'create role',
            'view role',
            'update role',
            'delete role',
            'create permission',
            'view permission',
            'update permission',
            'delete permission',
            'update status',
            'update priority',
            'show ticket',
            'view contact',
            'create contact',
            'update contact',
            'delete contact'
        ];

        // Assign permissions to Super Admin role
        foreach ($permissions as $permission) {
            $perm = Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
            $roles[AppHelper::USER_SUPER_ADMIN]->givePermissionTo($perm);
        }
    }
}
