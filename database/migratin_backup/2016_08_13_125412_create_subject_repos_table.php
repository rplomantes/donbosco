<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubjectReposTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subject_repos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('idno');
            $table->string('subjectcode');
            $table->string('level');
            $table->string('section');
            $table->float('grade');
            $table->integer('qtrperiod');
            $table->integer('schoolyear');
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
        Schema::drop('subject_repos');
    }
}
