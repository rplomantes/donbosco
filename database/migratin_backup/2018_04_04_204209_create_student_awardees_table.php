<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentAwardeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_awardees', function (Blueprint $table) {
            $table->increments('id');
            $table->string('idno');
            $table->string('level');
            $table->decimal('amount',10,2);
            $table->decimal('used',10,2);
            $table->string('schoolyear');
            $table->string('type');
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
        Schema::drop('student_awardees');
    }
}
