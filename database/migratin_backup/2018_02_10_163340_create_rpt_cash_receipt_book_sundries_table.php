<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRptCashReceiptBookSundriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rpt_cash_receipt_book_sundries', function (Blueprint $table) {
            $table->increments('id');
            $table->string('idno');
            $table->string('refno');
            $table->double('credit',10,2);
            $table->double('debit',10,2);
            $table->string('particular');
            $table->string('accountingcode');
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
        Schema::drop('rpt_cash_receipt_book_sundries');
    }
}
