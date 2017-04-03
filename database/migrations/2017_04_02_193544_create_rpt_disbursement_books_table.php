<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRptDisbursementBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rpt_disbursement_books', function (Blueprint $table) {
            $table->increments('id');
            $table->string('idno');
            $table->string('payee');
            $table->date('transactiondate');
            $table->string('voucherno');
            $table->decimal('voucheramount',10,2);
            $table->decimal('advances_employee',10,2);
            $table->decimal('cost_of_goods',10,2);
            $table->decimal('instructional_materials',10,2);
            $table->decimal('salaries_allowances',10,2);
            $table->decimal('personnel_dev',10,2);
            $table->decimal('other_emp_benefit',10,2);
            $table->decimal('office_supplies',10,2);
            $table->decimal('travel_expenses',10,2);
            $table->decimal('sundry_debit',10,2);
            $table->decimal('sundry_credit10,2');
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
        Schema::drop('rpt_disbursement_books');
    }
}
