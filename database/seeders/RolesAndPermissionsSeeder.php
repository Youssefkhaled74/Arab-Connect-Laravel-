<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $collection = collect([
            'About',
            'Blog',
            'Branch',
            'BranchChange',
            'Category',
            'User',
            'PaymentMethod',
            'Admin',
        ]);

        $collection->each(function ($item, $key) {
            // create permissions for each collection item
            Permission::create(['group' => $item, 'name' => 'viewAny' . $item]);
            Permission::create(['group' => $item, 'name' => 'view' . $item]);
            Permission::create(['group' => $item, 'name' => 'update' . $item]);
            Permission::create(['group' => $item, 'name' => 'create' . $item]);
            Permission::create(['group' => $item, 'name' => 'delete' . $item]);
            Permission::create(['group' => $item, 'name' => 'destroy' . $item]);
        });

        // Create a Super-Admin Role and assign all Permissions
        $role = Role::create(['name' => 'super-admin']);
        $role->givePermissionTo(Permission::all());

        // Give User Super-Admin Role
        $user = \App\Models\Admin::where('email', 'amr@gmail.com')->first();
        // $user->assignRole('super-admin');
    }
}
