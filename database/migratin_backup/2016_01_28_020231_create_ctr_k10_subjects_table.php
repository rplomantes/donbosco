<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCtrK10SubjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ctr_k10_subjects', function (Blueprint $table) {
            $table->increments('id');
            $table->string('subjectcode');
            $table->string('department');
            $table->string('level');
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
        Schema::drop('ctr_k10_subjects');
    }
}
