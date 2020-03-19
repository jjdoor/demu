<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerSupplierShipDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_supplier_ship_data', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('customer_supplier_id');
            $table->string('name', 50)->comment("船名");
            $table->integer('user_id')->comment('操作人');
            $table->smallInteger('status');
            $table->softDeletes();
            $table->timestamps();
            $table->engine = 'InnoDB';
            $table->unique(["customer_supplier_id", "name"]);
        });
        DB::statement("ALTER TABLE `customer_supplier_ship_data` comment'船公司船名详情表'"); // 表注释
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_supplier_ship_data');
    }
}
