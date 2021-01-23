<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('supplier_id');
	        $table->string('batch_no')->nullable();
	        $table->string('invoice_no')->nullable();
	        $table->string('supplier_ref_code')->nullable();
	        $table->date('received_date')->nullable();
	        $table->double('total_amount')->nullable();
            $table->integer('user_id');
            $table->enum('status',['requested','received','cancelled'])->default('requested');
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
        Schema::dropIfExists('supplier_orders');
    }
}
