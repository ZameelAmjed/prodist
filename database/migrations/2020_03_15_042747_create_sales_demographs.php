<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesDemographs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	if(!Schema::connection('mongodb')->hasTable('region'))
	    Schema::connection('mongodb')
	          ->create('region', function ($collection) {
	    });

	    if(!Schema::connection('mongodb')->hasTable('area'))
	    Schema::connection('mongodb')
	          ->create('area', function ($collection) {
	          });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mongodb')->dropIfExists('region');
        Schema::connection('mongodb')->dropIfExists('area');
    }
}
