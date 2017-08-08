<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCtrElectiveSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ctr_elective_sections', function (Blueprint $table) {
            $table->increments('id');
            $table->string('level');
            $table->string('elective');
            $table->string('elecode');
            $table->string('section');
            $table->string('adviser');
            $table->integer('sem');
            $table->integer('status');
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
        Schema::drop('ctr_elective_sections');
    }
}
