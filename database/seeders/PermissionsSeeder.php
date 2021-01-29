<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();


        $permissions = [
            'manage users',
            'manage departments', 'view departments',
            'manage countries', 'view countries',
            'manage states', 'view states',
            'manage cities', 'view cities',
            'manage employees', 'view employees',
        ];

        $per = Permission::all();

        foreach ($permissions as $permission1) {
            $found = false;
            foreach ($per as $permission2) {
                if ($permission2->name == $permission1) {
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                Permission::create(['name' => $permission1]);
            }
        }

        $roles = ['superadmin', 'officer'];
        $rol = Role::all();

        foreach ($roles as $role1) {
            $found = false;
            foreach ($rol as $role2) {
                if ($role2->name == $role1) {
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                Role::create(['name' => $role1]);
            }
        }

        $superadmin = Role::where('name', 'superadmin')->firstOrFail();

        foreach ($permissions as $permission1) {
            if (!$superadmin->hasPermissionTo($permission1)) $superadmin->givePermissionTo($permission1);
        }
        $officer = Role::where('name', 'officer')->firstOrFail();;

        $user  = \App\Models\User::where('email', 'developer@onesyntax.com')->first();
        $user = $user ? $user : new \App\Models\User([
            'firstname' => 'Admin',
            'lastname' => 'Developer',
            'username' => 'Developer',
            'email' => 'developer@onesyntax.com',
            'password' => Hash::make('developer'),
        ]);
        $user->saveOrFail();
        $user->assignRole($superadmin);
    }
}
