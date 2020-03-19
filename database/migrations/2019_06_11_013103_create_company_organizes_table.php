<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyOrganizesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_organizes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("name",50)->comment("组织结构名称");
            $table->smallInteger("status")->comment("0-禁用，1-启用");
            $table->smallInteger("parent_id");
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
        Schema::dropIfExists('company_organizes');
    }
}
