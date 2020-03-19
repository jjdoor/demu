<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePortsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ports', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('港口名称');
            $table->string('name_code')->comment('助记码');
            $table->text('country')->comment('国家');
//            $table->text('address')->comment('港口地址');
            $table->integer('user_id')->comment('操作人');
            $table->smallInteger('status')->comment('0:禁用,1:启用');
            $table->softDeletes();
            $table->timestamps();
            $table->engine = 'InnoDB';
        });
        DB::statement("ALTER TABLE `ports` comment'港口'"); // 表注释

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ports');
    }
}
