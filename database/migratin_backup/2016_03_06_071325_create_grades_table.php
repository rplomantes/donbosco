<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grades', function (Blueprint $table) {
            $table->increments('id');
            $table->string('idno');
            $table->string('department');
            $table->string('level');
            $table->string('course');
            $table->string('strand');
            $table->string('section');
            $table->string('subjectcode');
            $table->string('subjectname');
            $table->decimal('first_garding',7,4);
            $table->decimal('second_garding',7,4);
            $table->decimal('third_garding',7,4);
            $table->decimal('fourth_garding',7,4);
            $table->decimal('finalgrade',7,4);
            $table->string('remarks');
            $table->integer('status')->default(0);
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
        Schema::drop('grades');
    }
}
