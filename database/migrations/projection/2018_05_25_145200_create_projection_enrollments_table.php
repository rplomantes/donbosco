<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectionEnrollmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projection_enrollments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('level');
            $table->string('strand_course');
            $table->integer('projectde_count');
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
        Schema::drop('projection_enrollments');
    }
}
