<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * NOTE: This MUST be run before config is cached on the server.
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

        Role::create(['name' => 'paraplanner'])->givePermissionTo(['update cases','create notes']);
        Role::create(['name' => 'advisor'])->givePermissionTo(['create cases','delete cases','update cases','create notes','access IO data']);
        Role::create(['name' => 'admin'])->givePermissionTo(Permission::all());

        User::create([
            'name' => 'DBD Advisor Test',
            'email' => 'advisor@dbd.digital',
            'password' => bcrypt(env('ADMIN_PASSWORD')),
            'email_verified_at' => Carbon::now()
        ])->assignRole('advisor');

        User::create([
            'name' => 'DBD Admin',
            'email' => 'admin@dbd.digital',
            'password' => bcrypt(env('ADMIN_PASSWORD')),
            'email_verified_at' => Carbon::now()
        ])->assignRole('admin');

        User::create([
            'name' => 'DBD Paraplanner Test',
            'email' => 'paraplanning@dbd.digital',
            'password' => bcrypt(env('ADMIN_PASSWORD')),
            'email_verified_at' => Carbon::now()
        ])->assignRole('paraplanner');

    }
}
