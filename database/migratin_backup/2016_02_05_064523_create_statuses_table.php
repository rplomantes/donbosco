<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('statuses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('idno');
            $table->date('date_registered');
            $table->date('date_enrolled');
            $table->integer('status')->default(0);
            $table->string('department');
            $table->string('level');
            $table->string('track');
            $table->string('course');
            $table->string('section');
            $table->string('plan');
            $table->string('schoolyear');
            $table->string('period');
            $table->integer('isnew')->default(0);
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
        Schema::drop('statuses');
    }
}
