<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCtrK12SubjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ctr_k12_subjects', function (Blueprint $table) {
            $table->increments('id');
            $table->string('department');
            $table->string('track');
            $table->string('strand');
            $table->string('course');
            $table->string('level');
            $table->string('subjectcode');
            $table->string('subjectname');
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
        Schema::drop('ctr_k12_subjects');
    }
}
