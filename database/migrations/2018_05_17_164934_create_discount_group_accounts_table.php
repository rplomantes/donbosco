<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiscountGroupAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('discount_groups')) {
            Schema::create('discount_group_accounts', function (Blueprint $table) {
                $table->increments('id');
                $table->string('discount_id');
                $table->string('type');
                $table->string('appliedto');
                $table->string('discount_account');
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
        Schema::drop('discount_group_accounts');
    }
}
