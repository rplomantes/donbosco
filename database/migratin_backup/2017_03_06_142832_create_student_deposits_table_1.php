<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentDepositsTable extends Migration
{

    public function up()
    {
        Schema::create('student_deposits', function (Blueprint $table) {
            $table->increments('id');
            $table->date('transactiondate');
            $table->string('idno');
            $table->decimal('amount',8,2);
            $table->string('postedby');
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
        Schema::drop('student_deposits');
    }
}
