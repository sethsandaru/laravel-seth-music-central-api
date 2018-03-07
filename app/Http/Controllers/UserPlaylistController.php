<?php

namespace App\Http\Controllers;

use App\Music;
use App\UserPlaylist;
use App\UserPlaylistMusic;
use Illuminate\Http\Request;

class UserPlaylistController extends Controller
{
    /**
     * Get user playlists
     * @param $user_id
     */
    public function GetUserPlaylist($playlist_id)
    {
        $user_id = \request()->get('user_id');
        $myPlaylist = UserPlaylist::where([
                ['up_id', $playlist_id],
                ['user_id', $user_id]
            ])
            ->with('playlist_musics')->first();

        foreach ($myPlaylist->playlist_musics as $m)
            $m->info = $m->music;

        return response()->json(
            $myPlaylist
        );
    }

    public function CreatePlaylist($user_id)
    {
        $rq = \request();

        if ($rq->method() != "POST")
            return response()->json(['error' => 'method not allowed']);

        $title = $rq->post('title');
        if ($title == null)
            $title = "My New Noname Lyrics";

        $pl = new UserPlaylist;
        $pl->up_name = $title;
        $pl->user_id = $user_id;
        $pl->up_total_songs = 0;
        $pl->save();

        if ($pl->up_id > 0)
            return json_encode($pl);
        else
            return response()->json(['error' => 'failed to create']);
    }

    public function AddMusicToPlaylist($playlist_id)
    {
        $rq = \request();

        if ($rq->method() != "POST")
            return response()->json(['error' => 'method not allowed']);

        $music_id = $rq->post('music_id');
        $music = Music::find($music_id);
        $playlist = UserPlaylist::find($playlist_id);
        $existed = UserPlaylistMusic::where([
            ['music_id', $music_id],
            ['up_id', $playlist_id]
        ])->count();

        if ($music != null && $playlist != null && $existed == 0)
        {
            $new = new UserPlaylistMusic;
            $new->music_id = $music->music_id;
            $new->up_id = $playlist->up_id;
            $new->save();

            if ($new->upm_id > 0){
                $playlist->up_total_songs++;
                $playlist->save();

                return json_encode($new);
            }
            else
                return response()->json(['error' => 'Failed to create']);
        }
        else {
            return response()->json(['error' => 'Resource not available to add or this song already existed in your playlist!']);
        }
    }
}
