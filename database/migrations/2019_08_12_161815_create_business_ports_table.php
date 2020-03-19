<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessRoutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_route', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('segment_business_id');
            $table->integer('master_business_id');
            $table->integer('slaver_business_id');
            $table->integer('route_id');
            $table->timestamps();
            $table->softDeletes();
            $table->engine = 'InnoDB';
        });
        DB::statement("ALTER TABLE `business_port` comment'航线/业务板块关联表'"); // 表注释
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('business_ports');
    }
}
