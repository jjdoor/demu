<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerSupplierShipDataDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_supplier_ship_data_data', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('customer_supplier_ship_data_id');
            $table->integer('user_id');
            $table->integer('segment_business_id')->nullable();
            $table->integer('master_business_id')->nullable();
            $table->integer('slaver_business_id')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->engine = 'InnoDB';
        });
        DB::statement("ALTER TABLE `customer_supplier_ship_data_data` comment'船公司/船名/航次和业务板块关联表'"); // 表注释
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_supplier_ship_data_data');
    }
}
