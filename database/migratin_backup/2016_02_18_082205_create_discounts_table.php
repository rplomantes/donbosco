<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->increments('id');
            $table->date('transactiondate');
            $table->string('idno');
            $table->integer('refid');
            $table->string('plan');
            $table->string('discountcode');
            $table->string('description');
            $table->integer('tuitionfee');
            $table->integer('registrationfee');
            $table->integer('miscellaneousfee');
            $table->integer('elearningfee');
            $table->integer('departmentfee');
            $table->integer('bookfee');
            $table->decimal('amount',8,2);
            $table->date('duedate');
            $table->integer('is_applied')->default(0);
            $table->string('schoolyear');
            $table->string('period');
            $table->timestamps();
            $table->foreign('idno')
                    ->references('idno')
                    ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('discounts');
    }
}
