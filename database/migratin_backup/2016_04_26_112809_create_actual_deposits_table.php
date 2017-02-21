<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActualDepositsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('actual_deposits', function (Blueprint $table) {
            $table->increments('id');
            $table->date('transactiondate');
            $table->decimal('cbccash',2);
            $table->decimal('cbccheck',2);
            $table->decimal('bpi1cash',2);
            $table->decimal('bpi1check',2);
            $table->decimal('bpi2cash',2);
            $table->decimal('bpi2check',2);
            $table->decimal('variance',2);
            $table->timestamps();
            $table->string('postedby');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('actual_deposits');
    }
}
