<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ContractCustomerSupplier extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contract_customer_supplier', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->integerIncrements('id');
            $table->integer('contract_id');
            $table->integer('customer_supplier_id');
            $table->boolean('is_invoice')->comment('是否结算单位');
            $table->timestamps();
        });
        DB::statement("ALTER TABLE `contract_customer_supplier` comment'合同和客户供应商中间表'"); // 表注释
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contract_customer_supplier');
    }
}
