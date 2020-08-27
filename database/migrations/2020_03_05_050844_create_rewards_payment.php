<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRewardsPayment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rewards_payments', function (Blueprint $table) {
	        $table->increments('id');
	        $table->integer('electrician_id');
	        $table->float('points');
	        $table->string('transfer_type');
	        $table->string('comment')->nullable();
	        $table->string('confirmation_message')->nullable();
	        $table->date('payed_on');
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
        Schema::dropIfExists('rewards_payments');
    }
}
