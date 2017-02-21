<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCtrDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ctr_discounts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('discountcode');
            $table->string('description');
            $table->integer('tuitionfee');
            $table->integer('registrationfee');
            $table->integer('miscellaneousfee');
            $table->integer('elearningfee');
            $table->integer('departmentfee');
            $table->integer('bookfee');
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
        Schema::drop('ctr_discounts');
    }
}
