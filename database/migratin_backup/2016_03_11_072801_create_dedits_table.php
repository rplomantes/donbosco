<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dedits', function (Blueprint $table) {
            $table->increments('id');
            $table->string('idno');
            $table->date('transactiondate');
            $table->string('refno');
            $table->string('receiptno');
            $table->string('paymenttype');
            $table->string('bank_branch');
            $table->string('check_number');
            $table->integer('iscbc')->default(0);
            $table->decimal('amount',10,2);
            $table->decimal('checkamount',10,2);
            $table->decimal('receiveamount',10,2);
            $table->string('receivefrom');
            $table->string('depositto');
            $table->integer('isreverse')->default(0);
            $table->string('postedby');
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
        Schema::drop('dedits');
    }
}
