<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blocks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('height')->unsigned()->unique();
            $table->string('hash');
            $table->integer('version')->unsigned();
            $table->integer('nb_txs')->unsigned();
            $table->string('merkleroot');
            $table->bigInteger('fee')->unsigned();
            $table->bigInteger('vout_sum')->unsigned();
            $table->integer('size')->unsigned();
            $table->bigInteger('difficulty')->unsigned();
            $table->bigInteger('days_destroyed')->unsigned();
            $table->datetime('time_utc');
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
        Schema::drop('blocks');
    }
}
