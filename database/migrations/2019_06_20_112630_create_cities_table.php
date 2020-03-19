<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //数据来源https://blog.csdn.net/qq_38819293/article/details/81610683
        Schema::create('cities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('parent_id');
            $table->string('name', 50)->comment('名称');
            $table->smallInteger('type')->comment('等级');
            $table->unique('name');
            $table->timestamps();
            $table->engine = "InnoDB";
        });
        DB::statement("ALTER TABLE `cities` comment'国家/城市'"); // 表注释
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cities');
    }
}
