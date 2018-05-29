<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDepartmentBudgetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('department_budgets', function (Blueprint $table) {
            $table->increments('id');
            $table->string('department');
            $table->string('sub_department');
            $table->decimal('budget_amount',20,2);
            $table->string('acccountingcode');
            $table->string('fiscalyear');
            $table->string('approval_status');
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
        Schema::drop('department_budgets');
    }
}
