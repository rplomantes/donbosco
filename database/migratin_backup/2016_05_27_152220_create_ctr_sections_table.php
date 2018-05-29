<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCtrSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ctr_sections', function (Blueprint $table) {
            $table->increments('id');
            $table->string('level');
            $table->string('strand');
            $table->string('section');
            $table->string('status');
            $table->string('sortto');
            $table->string('adviser');
            $table->string('adviserId');
            $table->increments('schoolyear');
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
        Schema::drop('ctr_sections');
    }
}
