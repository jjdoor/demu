<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContractReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contract_reviews', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->integer('contracts_id');
            $table->integer("users_id")->comment("办理人");
            $table->smallInteger("status")->comment('办理状态');
            $table->smallInteger("result")->comment("办理结果-1:退签0:未办理1:同意");
            $table->string("opinion",300)->comment("办理意见");
            $table->smallInteger('process_type')->comment("审批进度类型1:合同草拟2:商务部评审会签3:业务部评审会签4:领导审批5:归档");
            $table->string('process_name', 100)->comment("审批进度名称");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contract_reviews');
    }
}
