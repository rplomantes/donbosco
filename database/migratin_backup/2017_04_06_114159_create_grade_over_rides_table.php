<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGradeOverRidesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grade_over_rides', function (Blueprint $table) {
            $table->increments('id');
            $table->string('idno');
            $table->string('level');
            $table->string('strand');
            $table->integer('subjecttype');
            $table->decimal('first_garding',7,4);
            $table->decimal('second_garding',7,4);
            $table->decimal('third_garding',7,4);
            $table->decimal('fourth_garding',7,4);
            $table->decimal('finalgrade',7,4);
            $table->string('schoolyear');
            $table->string('period');
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
        Schema::drop('grade_over_rides');
    }
}
