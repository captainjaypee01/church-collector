<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Database\Seeders\Traits\DisableForeignKeys;

/**
 * Class PermissionRoleTableSeeder.
 */
class PermissionRoleTableSeeder extends Seeder
{

    use DisableForeignKeys;

    /**
     * Run the database seed.
     *
     * @return void
     */
    public function run()
    {
        $this->disableForeignKeys();

        // Create Roles
        $admin = Role::create(['name' => 'admin', 'guard_name' => backpack_guard_name()]);
        $member = Role::create(['name' => 'member', 'guard_name' => backpack_guard_name()]);
        $collector = Role::create(['name' => 'collector', 'guard_name' => backpack_guard_name()]);
        $leader = Role::create(['name' => 'leader', 'guard_name' => backpack_guard_name()]);

        // Create Permissions
        $permissions = ['manage user', 'manage pledge', 'manage leader', 'manage location', 'manage project', 'manage report', 'manage permission', 'manage role'];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission, 'guard_name' => backpack_guard_name()]);
        }

        // ALWAYS GIVE ADMIN ROLE ALL PERMISSIONS
        $admin->givePermissionTo(Permission::all());
        $leader->givePermissionTo(['manage pledge', 'manage report']);
        $collector->givePermissionTo(['manage pledge']);
        $member->givePermissionTo(['manage pledge']);

        $this->enableForeignKeys();
    }
}
