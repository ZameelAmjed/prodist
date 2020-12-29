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
            'email' => 'admin@admin.com',
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
		    'email' => 'sales@prodist.com',
		    'password' => bcrypt('password'),
	    ]);
	    $user2->assignRole('sales executive');

	    $user3 = User::create([
		    'name' => 'Zahran Naseem',
		    'email' => 'zahrannaseem456@gmail.com',
		    'password' => bcrypt('password@997'),
	    ]);
	    $user3->assignRole('super admin');

	    $user4 = User::create([
		    'name' => 'Nafhan Naseem',
		    'email' => 'nafhannaseem85@gmail.com',
		    'password' => bcrypt('password@658'),
	    ]);
	    $user4->assignRole('super admin');

	    $user4 = User::create([
		    'name' => 'Nafhan Naseem',
		    'email' => 'nafhannaseem85@gmail.com',
		    'password' => bcrypt('password@658'),
	    ]);
	    $user4->assignRole('super admin');

	    $user5 = User::create([
		    'name' => 'Thahan Unais',
		    'email' => 'thahanmum@gmail.com',
		    'password' => bcrypt('password@897'),
	    ]);
	    $user5->assignRole('super admin');

	    $user6 = User::create([
		    'name' => 'Farhan Unais',
		    'email' => 'farhan.unais786@gmail.com',
		    'password' => bcrypt('password@585'),
	    ]);
	    $user6->assignRole('super admin');

    }
}
