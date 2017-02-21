<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('idno')->unique();
            $table->string('lastname');
            $table->string('firstname');
            $table->string('middlename');
            $table->string('extensionname');
            $table->string('gender');
            $table->string('email');
            $table->integer('accesslevel')->default(0);
            $table->integer('status')->default(0);
            $table->string('password', 60);
            $table->integer('reference_number')->default(1);
            $table->integer('receiptno');
            $table->rememberToken();
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
        Schema::drop('users');
    }
}
