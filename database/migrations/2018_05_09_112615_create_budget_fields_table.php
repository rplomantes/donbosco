<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBudgetFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('budget_fields', function (Blueprint $table) {
            $table->increments('id');
//            $table->string('type');
//            $table->string('accountgroup');    
//            $table->string('accountingcode');
//            $table->string('name');
//            $table->string('sort');
            
            $table->string('type');
            $table->string('entry_code');
            $table->string('group');
            $table->string('sub_group');
            $table->string('sort');
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
        Schema::drop('budget_fields');
    }
}
