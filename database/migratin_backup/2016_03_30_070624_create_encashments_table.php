<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEncashmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('encashments', function (Blueprint $table) {
            $table->increments('id');
            $table->date('transactiondate');
            $table->string('refno');
            $table->string('payee');
            $table->integer('isothercheck');
            $table->string('depositto');
            $table->string('bank_branch');
            $table->string('check_number');
            $table->decimal('amount',10,2);
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
        Schema::drop('encashments');
    }
}
