<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Music extends Model
{
    protected $table = 'musics';
    protected $primaryKey = 'music_id';
    public $timestamps = false;

    protected $fillable = [
        'music_name',
        'artist',
        'lyric',
        'file_url',
        'total_view',
        'created_at',
        'update_at',
        'user_id',
        'genre_id'
    ];



    /*
     * Relationship function
     */
    public function genre()
    {
        return $this->belongsTo('\App\Genre', 'genre_id', 'genre_id');
    }
}
