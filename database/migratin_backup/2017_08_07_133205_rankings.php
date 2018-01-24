<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Rankings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rankings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('idno');
            $table->integer('acad_1');
            $table->integer('acad_2');
            $table->integer('acad_3');
            $table->integer('acad_4');
            $table->integer('acad_final1');
            $table->integer('acad_final2');
            $table->integer('tech_1');
            $table->integer('tech_2');
            $table->integer('tech_3');
            $table->integer('tech_4');
            $table->integer('tech_final1');
            $table->integer('tech_final2');
            $table->integer('acad_level_1');
            $table->integer('acad_level_2');
            $table->integer('acad_level_3');
            $table->integer('acad_level_4');
            $table->integer('acad_level_final1');
            $table->integer('acad_level_final2');
            $table->integer('tech_level_1');
            $table->integer('tech_level_2');
            $table->integer('tech_level_3');
            $table->integer('tech_level_4');
            $table->integer('tech_level_final1');
            $table->integer('tech_level_final2');          
            $table->integer('acad_oa_final1');
            $table->integer('acad_oa_final2');            
            $table->integer('tech_oa_final1');
            $table->integer('tech_oa_final2');            
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
        //
    }
}
