<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCtrAssessmentAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ctr_assessment_accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('level');
            $table->integer('accountingcode');
            $table->string('accountname');
            $table->string('description');
            $table->string('receiptdetails');
            $table->string('main_department');
            $table->string('sub_department');
            $table->double('amount',10,2);
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
        Schema::drop('ctr_assessment_accounts');
    }
}
