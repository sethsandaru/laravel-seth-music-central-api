<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserPlaylistMusicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('user_playlist_musics', function (Blueprint $table) {
            $table->increments( 'upm_id');
            $table->integer('music_id')->unsigned();
            $table->integer('up_id')->unsigned();

            $table->foreign('music_id')->references('music_id')->on('musics');
            $table->foreign('up_id')->references('up_id')->on('user_playlists');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_playlist_musics');
    }
}
