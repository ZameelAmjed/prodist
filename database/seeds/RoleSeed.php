<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
	    $rolesales = Role::create(['name' => 'sales executive']);
	    $rolesales->givePermissionTo('sales_executive');
        $role = Role::create(['name' => 'administrator']);
        $role->givePermissionTo('users_manage');
	    //$role->givePermissionTo('sales_executive');
	    $role2 = Role::create(['name' => 'super admin']);
	    $role2->givePermissionTo('users_manage');
	    $role2->givePermissionTo('super_admin');
	    //$role2->givePermissionTo('sales_executive');
    }
}
