<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoleReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_reviews', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('model')->comment('模块名称，一般和表名对应');
            $table->string('name')->nullable()->comment('审批名称');
            $table->integer('foreign_key')->comment('外键,model表对应的自增id');
            $table->integer('preview_role_id')->nullable()->comment('提交审核者角色');
            $table->integer('preview_user_id')->nullable()->comment('提交审核者');
            $table->integer('review_role_id')->nullable()->comment('审核者角色');
            $table->integer('review_user_id')->nullable()->comment('审核者');
            $table->string('preview_suggestion')->nullable()->comment('提交者建议');
            $table->string('review_suggestion')->nullable()->comment('审核者建议');
            $table->smallInteger('status')->nullable()->comment('null:草稿，-1:退签，0:已提交，未审批，1:审批');
//            $table->smallInteger('sort')->comment('排序，小数字在前');
            $table->timestamps();
            $table->engine = 'InnoDB';
        });
        DB::statement("ALTER TABLE `role_reviews` comment'审批过程'"); // 表注释
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('role_reviews');
    }
}
