<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRptCashreceiptBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rpt_cashreceipt_books', function (Blueprint $table) {
            $table->increments('id');
            $table->string('idno');
            $table->string('refno');
            $table->string('from');
            $table->date('transactiondate');
            $table->string('receiptno');
            $table->decimal('cash',10,2);
            $table->decimal('discount',10,2);
            $table->decimal('fape',10,2);
            $table->decimal('dreservation',10,2);
            $table->decimal('deposit',10,2);
            $table->decimal('elearning',10,2);
            $table->decimal('misc',10,2);
            $table->decimal('book',10,2);
            $table->decimal('dept',10,2);
            $table->decimal('registration',10,2);
            $table->decimal('tuition',10,2);
            $table->decimal('creservation',10,2);
            $table->decimal('csundry',10,2);
            $table->integer('totalindic');
            $table->integer('isreverse');
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
        Schema::drop('rpt_cashreceipt_books');
    }
}
