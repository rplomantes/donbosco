<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCtrFeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ctr_fees', function (Blueprint $table) {
            $table->increments('id');
            $table->string('department');
            $table->string('level');
            $table->string('strand');
            $table->string('course');
            $table->string('acctcode');
            $table->string('acctname');
            $table->string('subsidiary');
            $table->decimal('amount',10,2);
            $table->integer('categoryswitch');
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
        Schema::drop('ctr_fees');
    }
}
