<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMusicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        define('PREFIX', 'music_');

        Schema::create('musics', function (Blueprint $table) {
            $table->increments('music_id');
            $table->integer('genre_id')->unsigned();;
            $table->integer('user_id')->unsigned();;
            $table->string('music_name');
            $table->string('artist');
            $table->longText('lyric');
            $table->string('file_url');
            $table->integer('total_view');
            $table->bigInteger('created_at');
            $table->bigInteger('update_at');

            $table->foreign('genre_id')->references('genre_id')->on('genres');
            $table->foreign('user_id')->references('id')->on('users');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('musics');
    }
}
