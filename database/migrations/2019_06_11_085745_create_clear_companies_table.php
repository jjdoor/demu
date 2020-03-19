<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClearCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /** @noinspection PhpUndefinedClassInspection */
        Schema::create('clear_companies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 50)->comment("全称");
            $table->string('name_code', 50)->comment("助记码");
            $table->string('name_abbreviation', 50)->comment("简称");
            $table->smallInteger('status');
            $table->integer('user_id')->comment('操作员');
            $table->softDeletes();
            $table->timestamps();
            $table->unique("name");
            $table->unique("name_code");
            $table->unique("name_abbreviation");
            $table->engine = 'InnoDB';
        });
        DB::statement("ALTER TABLE `clear_companies` comment'结算公司'"); // 表注释
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        /** @noinspection PhpUndefinedClassInspection */
        Schema::dropIfExists('clear_companies');
    }
}
