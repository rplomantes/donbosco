<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDisbursementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('disbursements', function (Blueprint $table) {
            $table->increments('id');
            $table->date("transactiondate");
            $table->string("bank");
            $table->string("refno");
            $table->string("voucherno");
            $table->string("checkno");
            $table->decimal("amount",10,2);
            $table->integer('isreverse')->default(0);
            $table->string("postedby");
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
        Schema::drop('disbursements');
    }
}
