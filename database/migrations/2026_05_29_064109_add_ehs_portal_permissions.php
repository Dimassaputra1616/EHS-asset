<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // New EHS permissions
        $permissions = [
            'requests.view',
            'requests.create',
            'requests.manage',
            'damage_reports.view',
            'damage_reports.create',
            'damage_reports.manage',
        ];

        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName, 'guard_name' => 'web']);
        }

        // Auto-assign all of them to admin
        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole) {
            $adminRole->givePermissionTo($permissions);
        }

        // Staff can create & view requests and damage reports
        $staffRole = Role::where('name', 'staff')->first();
        if ($staffRole) {
            $staffRole->givePermissionTo([
                'requests.view',
                'requests.create',
                'damage_reports.view',
                'damage_reports.create',
            ]);
        }

        // Karyawan can create & view requests and damage reports
        $karyawanRole = Role::where('name', 'karyawan')->first();
        if ($karyawanRole) {
            $karyawanRole->givePermissionTo([
                'requests.view',
                'requests.create',
                'damage_reports.view',
                'damage_reports.create',
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        
        $permissions = [
            'requests.view',
            'requests.create',
            'requests.manage',
            'damage_reports.view',
            'damage_reports.create',
            'damage_reports.manage',
        ];

        foreach ($permissions as $permissionName) {
            Permission::where('name', $permissionName)->delete();
        }
    }
};
