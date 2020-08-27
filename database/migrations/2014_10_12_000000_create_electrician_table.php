<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElectricianTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('electricians', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('nic');
            $table->string('celebration')->nullable();
            $table->string('telephone')->nullable();
            $table->string('block')->nullable();
            $table->string('street')->nullable();
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            $table->string('region')->nullable();
            $table->string('area')->nullable();
            $table->string('member_code')->nullable();
            $table->integer('dealer_id')->nullable();
            $table->string('language')->nullable();
            $table->string('bank_account_no')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_city')->nullable();
            $table->string('bank_code')->nullable();
            $table->string('photo')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->integer('approved_by')->nullable();
            $table->enum('status',['active','pending','reject'])->default('active');
            $table->integer('products_count')->default(0);
            $table->double('points',10, 2)->default(0);
            $table->double('float_points',10, 2)->default(0);
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
        Schema::dropIfExists('electricians');
    }
}
