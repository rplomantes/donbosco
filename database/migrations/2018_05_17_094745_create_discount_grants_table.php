<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiscountGrantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('discount_grants')) {
            Schema::create('discount_grants', function (Blueprint $table) {
                $table->increments('id');
                $table->string('discount_type');
                $table->string('idno');
                $table->string('schoolyear');
                $table->decimal('tuitionfee',10,2);
                $table->decimal('registrationfee',10,2);
                $table->decimal('miscfee',10,2);
                $table->decimal('elearningfee',10,2);
                $table->decimal('departmentfee',10,2);
                $table->decimal('bookfee',10,2);
                $table->string('createdBy');
                $table->string('approvedBy');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('discount_grants');
    }
}
