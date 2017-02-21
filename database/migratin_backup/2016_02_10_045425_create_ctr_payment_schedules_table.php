<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCtrPaymentSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ctr_payment_schedules', function (Blueprint $table) {
            $table->increments('id');
            $table->string('plan');
            $table->string('department');
            $table->string('level');
            $table->string('track');
            $table->string('strand');
            $table->string('course');
            $table->integer('categoryswitch');
            $table->string('acctcode');
            $table->string('description');
            $table->string('receipt_details');
            $table->decimal('amount',10,2);
            $table->decimal('discount',10,2);
            $table->string('schoolyear');    
            $table->string('period');
            $table->date('duedate');
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
        Schema::drop('ctr_payment_schedules');
    }
}
