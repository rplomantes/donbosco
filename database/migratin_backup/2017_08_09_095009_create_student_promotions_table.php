<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentPromotionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_promotions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('idno');
            $table->string('admission');
            $table->string('conduct');
            $table->string('academic');
            $table->string('technical');
            $table->string('schoolyear');
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
        Schema::drop('student_promotions');
    }
}
