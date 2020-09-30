<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupplierOrderItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplier_order_items', function (Blueprint $table) {
            $table->bigIncrements('id');
	        $table->integer('supplier_order_id');
	        $table->integer('product_id');
	        $table->integer('requested_units');
	        $table->date('expiry_date')->nullable();
	        $table->integer('received_units')->default(0);
	        $table->integer('unit_price')->nullable();
	        $table->double('discount')->nullable();
	        $table->integer('total_price')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('supplier_order_items');
    }
}
