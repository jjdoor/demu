<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessContractTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_contract', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('contract_id');
            $table->integer('segment_business_id')->comment('业务板块id');
            $table->integer('master_business_id')->comment('主业务类型id');
            $table->integer('slaver_business_id')->comment('子业务类型id');
            $table->integer('charge_rule_id')->comment('价格协议号，其实就是收费规则');
            $table->integer('user_id');
            $table->timestamps();
            $table->engine = 'InnoDB';
        });
        DB::statement("ALTER TABLE `business_contract` comment'合同和业务模块关联表'"); // 表注释
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contract_data');
    }
}
