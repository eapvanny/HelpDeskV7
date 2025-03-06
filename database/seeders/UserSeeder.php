<?php

namespace Database\Seeders;

use App\Http\Helpers\AppHelper;
use App\Models\User;
use App\Models\UserRole;
use App\Models\Department;
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
        // Define departments with Khmer names and abbreviations
        $departments = [
            AppHelper::IT_DEPARTMENT => [
                'code' => 'IT001',
                'name' => 'នាយកដ្ឋានព័ត៌មានវិទ្យា',
                'name_in_latin' => 'IT Department',
                'abbreviation' => 'Dep.IT'
            ],
            AppHelper::SALE_DEPARTMENT => [
                'code' => 'SAL001',
                'name' => 'នាយកដ្ឋានផ្នែកលក់',
                'name_in_latin' => 'Sale Department',
                'abbreviation' => 'Dep.Sale'
            ],
            AppHelper::FINANCE_DEPARTMENT => [
                'code' => 'FIN001',
                'name' => 'នាយកដ្ឋានហិរញ្ញវត្ថុ',
                'name_in_latin' => 'Finance Department',
                'abbreviation' => 'Dep.Fin'
            ],
            AppHelper::MARKETING_DEPARTMENT => [
                'code' => 'MKT001',
                'name' => 'នាយកដ្ឋានទីផ្សារ',
                'name_in_latin' => 'Marketing Department',
                'abbreviation' => 'Dep.Mkt'
            ],
            AppHelper::PRODUCTION_DEPARTMENT => [
                'code' => 'PROD001',
                'name' => 'នាយកដ្ឋានផលិតកម្ម',
                'name_in_latin' => 'Production Department',
                'abbreviation' => 'Dep.Prod'
            ],
            AppHelper::WH_LOGISTIC_DEPARTMENT => [
                'code' => 'WH001',
                'name' => 'នាយកដ្ឋានឃ្លាំង និងដឹកជញ្ជូន',
                'name_in_latin' => 'WH & Logistic Department',
                'abbreviation' => 'Dep.WH'
            ],
            AppHelper::HR_DEPARTMENT => [
                'code' => 'HR001',
                'name' => 'នាយកដ្ឋានធនធានមនុស្ស',
                'name_in_latin' => 'Human Resource Department',
                'abbreviation' => 'Dep.HR'
            ],
        ];

        // Seed departments
        foreach ($departments as $id => $data) {
            Department::firstOrCreate(
                ['code' => $data['code']], // Using code as unique identifier
                [
                    'name' => $data['name'],
                    'name_in_latin' => $data['name_in_latin'],
                    'abbreviation' => $data['abbreviation']
                ]
            );
        }

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
                'department_id' => AppHelper::IT_DEPARTMENT,
                'staff_id_card' => '8332',
                'position' => 'Developer',
                'gender' => AppHelper::GENDER_MALE,
                'status' => AppHelper::STATUS_OPEN,
                'role_id' => AppHelper::USER_SUPER_ADMIN,
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
            'view ticket',
            'view status',
            'view priority',
            'view user',
            'view role',
            'view permission',
            'view contact',
            'create department',
            'create ticket',
            'create user',
            'create role',
            'create permission',
            'create contact',
            'update department',
            'update ticket',
            'update status',
            'update priority',
            'update user',
            'update role',
            'update contact',
            'delete permission',
            'update permission',
            'delete department',
            'delete ticket',
            'delete user',
            'delete role',
            'delete contact',
            'show ticket',
        ];

        // Assign permissions to Super Admin role
        foreach ($permissions as $permission) {
            $perm = Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
            $roles[AppHelper::USER_SUPER_ADMIN]->givePermissionTo($perm);
        }
    }
}