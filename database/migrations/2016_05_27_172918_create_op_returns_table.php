<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOpReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('op_returns', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('height')->unsigned();
            $table->string('txid');
            $table->integer('txoffset')->unsigned();
            $table->string('script');
            $table->string('hex');
            $table->string('ascii');
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
        Schema::drop('op_returns');
    }
}
