<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCtrDropdownOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('ctr_dropdown_options')) {
        Schema::create('ctr_dropdown_options', function (Blueprint $table) {
            $table->increments('id');
            $table->string('category');
            $table->string('item');
            $table->string('value');
        });            
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ctr_dropdown_options');
    }
}
