<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_infos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('idno');
            $table->date('birthDate');
            $table->string('birthPlace');
            $table->string('citizenship');
            $table->string('religion');
            $table->string('status');
            $table->string('address1');
            $table->string('address2');
            $table->string('address3');
            $table->string('address4');
            $table->string('address5');
            $table->string('address6');
            $table->string('address7');
            $table->string('address8');
            $table->string('address9');
            $table->string('phone1');
            $table->string('phone2');
            $table->string('lastattended');
            $table->string('lastlevel');
            $table->string('lastyear');
            $table->integer('countboys');
            $table->integer('countgirls');
            $table->string('lrn');
            
            
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
        Schema::drop('student_infos');
    }
}
