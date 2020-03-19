<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfitMinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profit_mins', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('clear_companies_id')->comment('结算公司id');
            $table->integer('businesses_id')->comment('业务板块');
            $table->
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('profit_mins');
    }
}
