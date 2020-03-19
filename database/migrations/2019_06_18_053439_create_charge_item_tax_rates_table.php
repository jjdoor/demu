<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChargeItemTaxRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('charge_item_tax_rates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('segment_businesses_id')->comment('业务板块,level-0');
            $table->integer('master_businesses_id')->comment('主业务板块,level-1');
            $table->integer('slaver_businesses_id')->comment('子业务板块');
            $table->integer('charge_items_id')->comment('费用科目');
            $table->integer("invoice_types_id")->comment('开票类型id');
            $table->boolean('is_tax_free')->comment('是否免税');
            $table->softDeletes();
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
        Schema::dropIfExists('charge_item_tax_rates');
    }
}
