<?php

use Illuminate\Database\Seeder;
use App\User;

class UserSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@chint.com',
            'password' => bcrypt('password'),
        ]);
        $user->assignRole('administrator');

	    $user2 = User::create([
		    'name' => 'Super Admin',
		    'email' => 'zameelamjed@yahoo.com',
		    'password' => bcrypt('password'),
	    ]);
	    $user2->assignRole('super admin');

	    $user2 = User::create([
		    'name' => 'Sales Executive',
		    'email' => 'sales@chint.com',
		    'password' => bcrypt('password'),
	    ]);
	    $user2->assignRole('sales executive');

    }
}
