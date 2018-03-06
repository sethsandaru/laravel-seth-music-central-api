<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserPlaylistMusic extends Model
{
    protected $table = 'user_playlist_musics';
    protected $primaryKey = 'upm_id';
    public $timestamps = false;
    protected $fillable = [
        'music_id',
        'up_id',
    ];

    // relationship function
    public function music()
    {
        return $this->belongsTo('App\Music', 'music_id', 'music_id');
    }

    public function playlist()
    {
        return $this->belongsTo('App\UserPlaylist', 'up_id', 'up_id');
    }
}
