<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEntranceSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entrance_schedules', function (Blueprint $table) {
            $table->increments('id');
            $table->string('level');
            $table->string('batch');
            $table->string('date');
            $table->string('time_start');
            $table->string('time_end');
            $table->integer('max_examinee');
            $table->string('schoolyear');
            $table->softDeletes();
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
        Schema::drop('entrance_schedules');
    }
}
