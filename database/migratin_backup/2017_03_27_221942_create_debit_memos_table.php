<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDebitMemosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('debit_memos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('refno');
            $table->string('voucherno');
            $table->date('transactiondate');
            $table->decimal('amount',10,2);
            $table->string('remarks');
            $table->integer('isreverse')->default(0);
            $table->string('postedby');
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
        Schema::drop('debit_memos');
    }
}
