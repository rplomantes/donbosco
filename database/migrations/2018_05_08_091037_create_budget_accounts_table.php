<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBudgetAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('budget_accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type');
            $table->string('entry_code');
            $table->decimal('amount',20,2);
            $table->string('group');
            $table->string('sub_group');
            $table->string('department');
            $table->string('sub_department');
            $table->string('sort');
            $table->integer('fiscalyear');
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
        Schema::drop('budget_accounts');
    }
}
