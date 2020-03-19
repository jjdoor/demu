<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContainerAddressDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('container_address_data', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('container_addresses_id');
            $table->integer('segment_businesses_id');
//            $table->string('segment_business_name');
            $table->integer('master_businesses_id');
//            $table->string('master_businesses_name');
            $table->integer('slaver_businesses_id');
//            $table->string('slaver_businesses_name');
            $table->timestamps();
            $table->engine = 'InnoDB';
        });
        DB::statement("ALTER TABLE `container_address_data` comment'送箱装箱地点和业务关系'"); // 表注释
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('container_address_data');
    }
}
