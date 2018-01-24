<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCtrAutoEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ctr_auto_entries', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('entry_type');
            $table->string('indic');
            $table->string('debit');
            $table->string('credit');
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
        Schema::drop('ctr_auto_entries');
    }
}
