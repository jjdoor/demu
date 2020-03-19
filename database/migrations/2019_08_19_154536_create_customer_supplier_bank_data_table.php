<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerSupplierBankDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_supplier_bank_data', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('customer_supplier_id');
            $table->string('name', 50)->comment('银行名称');
            $table->string('account', 100)->comment('银行账号');
            $table->enum('currency', ['CNY', 'USD'])->nullable()->comment('币种');
            $table->softDeletes();
            $table->timestamps();
            $table->engine = 'InnoDB';
            $table->unique(["customer_supplier_id", "name", "account", "currency"], "c_name_account");
        });
        DB::statement("ALTER TABLE `contracts` comment'开户行'"); // 表注释
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_supplier_bank_data');
    }
}
