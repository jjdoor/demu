<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFreightForwardersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('freight_forwarders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('sn')->comment('业务号');
            $table->string('from')->nullable()->comment('揽货性质,company:公司揽货,person:销售揽货');
            $table->integer('segment_business_id')->nullable()->comment('业务板块');
            $table->integer('master_business_id')->nullable()->comment('主业务类型');
            $table->integer('slaver_business_id')->nullable()->comment('子业务类型');
            $table->integer('customer_supplier_id')->nullable()->comment('客户供应商id');
            $table->integer('clear_company_id')->nullable()->comment('结算单位id');
            $table->integer('created_user_id')->comment('操作人id');
            $table->string('created_user_name')->comment('操作人');
            $table->timestamp('created_created_at')->nullable()->comment('创建时间');
            $table->integer('service_user_id')->comment('客服id');
            $table->string('service_user_name')->comment('客服');
            $table->integer('sale_user_id')->comment('销售id');
            $table->string('sale_user_name')->comment('销售');
            $table->softDeletes();
            $table->timestamps();
            $table->engine = 'InnoDB';
        });
        DB::statement("ALTER TABLE `freight_forwarders` comment'货运代理订单'"); // 表注释
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('freight_forwarders');
    }
}
