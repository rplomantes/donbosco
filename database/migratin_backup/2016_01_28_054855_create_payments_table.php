<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
           
            $table->increments('id');
            $table->string('idno');
            $table->date('transactiondate');
            $table->string('refno');
            $table->string('receiptno');
            $table->integer('categoryswitch');
            $table->string('acctcode');
            $table->string('description');
            $table->string('receipt_details');
            $table->decimal('amount',10,2);
            $table->integer('paymenttype')->default(0);
            $table->integer('isreverse')->default(0);
            $table->integer('iscurrent')->default(1);
            $table->date('duedate');
            $table->string('schoolyear');
            $table->string('period');
            $table->string('postedby');
            $table->string('reverseby');
            $table->date('reversedate');
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
        Schema::drop('payments');
    }
}
