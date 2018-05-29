<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssessmentOverridesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assessment_overrides', function (Blueprint $table) {
            $table->increments('id');
            $table->string('idno');
            $table->integer('Allow');
            $table->string('schoolyear');
            $table->string('postedBy');
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
        Schema::drop('assessment_overrides');
    }
}
