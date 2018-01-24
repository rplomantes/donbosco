<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCtrEntranceCriterionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ctr_entrance_criterions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('level');
            $table->string('criterion');
            $table->string('sub_criterion');
            $table->integer('category');
            $table->integer('grade');
            
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
        Schema::drop('ctr_entrance_criterions');
    }
}
