<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePrevSchoolRecsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prev_school_recs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('idno');
            $table->string('schoolyear');
            $table->string('dateEntered');
            $table->string('dateLeft');
            $table->string('level');
            $table->float('dayp',7,4);
            $table->float('finalrate',7,4);
            $table->string('status');
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
        Schema::drop('prev_school_recs');
    }
}
