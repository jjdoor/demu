<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContainerAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('container_addresses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('address',255)->comment('装箱地点');
            $table->boolean("is_up")->comment("是否装箱地点");
            $table->boolean("is_down")->comment("是否送箱地点");
//            $table->string("businesses_id_string")->comment("所属业务板块，中间用英文逗号分隔");
            $table->smallInteger("status");
            $table->softDeletes();
            $table->timestamps();
            $table->engine = 'InnoDB';
        });
        DB::statement("ALTER TABLE `container_addresses` comment'送箱装箱地点维护'"); // 表注释
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('container_addresses');
    }
}
