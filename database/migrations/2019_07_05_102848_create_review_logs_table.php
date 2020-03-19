<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReviewLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('review_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('model')->comment('审批模块名称');
            $table->string('name')->comment("审批名称");
            $table->integer('foreign_key')->comment("外键，model表对应的自增id");
            $table->integer('role_id')->comment('审核者角色');
            $table->integer('user_id')->nullable()->comment('审核者');
            $table->string('suggestion')->nullable()->comment('意见建议，status=1表示对下一步的建议,status=-1表示对上一步的建议');
            $table->smallInteger('status')->default(0)->comment('审核状态 -1:退签，0:未审核，草稿状态，1:同意');
            $table->timestamps();
            $table->engine = 'InnoDB';
        });
        DB::statement("ALTER TABLE `review_logs` comment'审批日志'"); // 表注释
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('review_logs');
    }
}
