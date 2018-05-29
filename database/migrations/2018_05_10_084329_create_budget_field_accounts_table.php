<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBudgetFieldAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('budget_field_accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('field');
            $table->string('accountingcode');
            $table->string('accountname');
            $table->string('subsidiary');
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
        Schema::drop('budget_field_accounts');
    }
}
