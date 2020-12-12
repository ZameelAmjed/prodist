<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(PermissionSeed::class);
        $this->call(RoleSeed::class);
        $this->call(UserSeed::class);
        $this->call(StoreSeed::class);
	    $this->call(LocationSeed::class);
	    $this->call(ProductsSeed::class);
	    $this->call(SupplierSeed::class);
	    $this->call(OrderSeed::class);
	    $this->call(PaymentSeed::class);
    }
}
