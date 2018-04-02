<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEscAwardeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('esc_awardees', function (Blueprint $table) {
            $table->increments('id');
            $table->string('idno');
            $table->decimal('amount',10,2);
            $table->integer('schoolyear');
            $table->integer('status');
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
        Schema::drop('esc_awardees');
    }
}
