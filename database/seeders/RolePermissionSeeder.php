<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */


    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        $permissions = [
            'config.manage',
            'users.manage',
            'roles.manage',
            'assets.view', 'assets.create', 'assets.edit', 'assets.delete',
            'consumables.view', 'consumables.create', 'consumables.edit', 'consumables.delete',
            'master.manage', // categories, locations, suppliers
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // create roles and assign existing permissions
        $roleAdmin = Role::firstOrCreate(['name' => 'admin']);
        $roleAdmin->givePermissionTo(Permission::all());

        $roleStaff = Role::firstOrCreate(['name' => 'staff']);
        $roleStaff->givePermissionTo([
            'assets.view', 'assets.create', 'assets.edit',
            'consumables.view', 'consumables.create', 'consumables.edit',
            'master.manage'
        ]);

        $roleKaryawan = Role::firstOrCreate(['name' => 'karyawan']);
        $roleKaryawan->givePermissionTo([
            'assets.view',
            'consumables.view',
        ]);

        // Create default admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@hse.local'],
            [
                'name' => 'Admin HSE',
                'password' => Hash::make('password'),
            ]
        );
        $admin->assignRole($roleAdmin);

        // Create default staff
        $staff = User::firstOrCreate(
            ['email' => 'staff@hse.local'],
            [
                'name' => 'Staff HSE',
                'password' => Hash::make('password'),
            ]
        );
        $staff->assignRole($roleStaff);

        // Create default karyawan
        $karyawan = User::firstOrCreate(
            ['email' => 'karyawan@hse.local'],
            [
                'name' => 'Karyawan HSE',
                'password' => Hash::make('password'),
            ]
        );
        $karyawan->assignRole($roleKaryawan);
    }
}
