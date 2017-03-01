<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeletedAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deleted_accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('idno');
            $table->string('department');
            $table->string('level');
            $table->string('course');
            $table->string('track');
            $table->string('strand');
            $table->date('transactiondate');
            $table->integer('categoryswitch');
            $table->string('acctcode');
            $table->string('description');
            $table->string('receipt_details');
            $table->decimal('amount',10,2);
            $table->decimal('payment',10,2);
            $table->decimal('plandiscount',10,2);
            $table->decimal('debitmemo',10,2);
            $table->decimal('otherdiscount');
            $table->string('discountcode');
            $table->string('schoolyear');
            $table->string('period');
            $table->date('duedate');
            $table->string('postedby');
            $table->string('remark');
            $table->string('deleted');
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
        Schema::drop('deleted_accounts');
    }
}