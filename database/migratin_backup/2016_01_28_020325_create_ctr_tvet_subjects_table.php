<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCtrTvetSubjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ctr_tvet_subjects', function (Blueprint $table) {
            $table->increments('id');
            $table->string('subjectcode');
            $table->string('coursecode');
            $table->string('coursename');
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
        Schema::drop('ctr_tvet_subjects');
    }
}
