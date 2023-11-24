<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        Permission::create(['name' => 'alter and delete users']);
        Permission::create(['name' => 'create cases']);
        Permission::create(['name' => 'delete cases']);
        Permission::create(['name' => 'update cases']);
        Permission::create(['name' => 'create notes']);
        Permission::create(['name' => 'access audit log']);
        Permission::create(['name' => 'access IO data']);

        

    }
}
