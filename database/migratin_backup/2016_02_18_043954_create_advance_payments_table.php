<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdvancePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('advance_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->date('transactiondate');
            $table->string('idno');
            $table->decimal('amount',8,2);
            $table->string('postedby');
            $table->integer('status')->default(0);
            $table->timestamps();
            $table->foreign('idno')
                       ->references('idno')
                        ->on('users');    
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('advance_payments');
    }
}
