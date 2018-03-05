<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserPlaylist extends Model
{
    protected $table = 'user_playlists';
    protected $primaryKey = 'up_id';
    protected $fillable = [
        'up_name',
        'up_total_songs',
        'user_id',
        'created_at',
        'updated_at',
    ];

    // relationship function

    /**
     * Return user
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id','id');
    }

    /**
     * Total music in this playlist
     */
    public function playlist_musics()
    {
        return $this->hasMany('App\UserPlaylistMusic', 'up_id', 'up_id');
    }
}
