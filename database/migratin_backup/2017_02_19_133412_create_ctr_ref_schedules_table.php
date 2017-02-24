<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCtrRefSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ctr_ref_schedules', function (Blueprint $table) {
            $table->increments('id');
            $table->string('plan');
            $table->integer('duetype');
            $table->string('deparment');
            $table->string('level');
            $table->string('strand');
            $table->string('course');
            $table->string('acctcode');
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
        Schema::drop('ctr_ref_schedules');
    }
}
