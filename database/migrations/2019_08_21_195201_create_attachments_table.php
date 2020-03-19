<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attachments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("model")->comment('模块名，一般对应表名');
            $table->integer("foreign_key")->comment('模块名外键');
            $table->string('name')->comment('原文件重新命名后，在阿里云oss保存');
            $table->string('original_name')->comment('原文件名字');
            $table->string('ext')->comment('文件扩展名');
            $table->integer('size')->comment('文件大小');
            $table->softDeletes();
            $table->timestamps();
            $table->unique(["model", "foreign_key"]);
            $table->engine = 'InnoDB';
        });
        DB::statement("ALTER TABLE `attachments` comment'附件表'"); // 表注释
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attachments');
    }
}
