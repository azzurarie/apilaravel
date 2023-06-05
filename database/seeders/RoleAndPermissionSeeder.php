<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roleSuperAdmin = Role::create(['name' => 'superadmin']);
        $customer = Role::create(['name' => 'customer']);
        $driver = Role::create(['name' => 'driver']);

        Permission::create(['name' => 'manage_users']);
        Permission::create(['name' => 'manage_roles']);
        Permission::create(['name' => 'manage_products']);
        Permission::create(['name' => 'manage_product_category']);
        Permission::create(['name' => 'manage_doctor_type']);
        Permission::create(['name' => 'get_customer']);
        Permission::create(['name' => 'order']);


        $driver->syncPermissions('get_customer');
        $customer->syncPermissions('order');
        $roleSuperAdmin->syncPermissions([
            'manage_users', 
            'manage_roles',
            'manage_products',
            'manage_product_category',
            'manage_doctor_type',
        ]);
    }
}
