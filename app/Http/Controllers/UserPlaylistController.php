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
    public function GetUserPlaylist($user_id)
    {
        $myPlaylist = UserPlaylist::where('user_id', $user_id)->with('playlist_musics')->get();

        foreach ($myPlaylist as $item)
            foreach ($item->playlist_musics as $m)
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
        $pl->title = $title;
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

        if ($music != null && $playlist != null)
        {
            $new = new UserPlaylistMusic;
            $new->music_id = $music->music_id;
            $new->up_id = $playlist->up_id;
            $new->save();

            if ($new->upm_id > 0)
                return json_encode($new);
            else
                return response()->json(['error' => 'failed to create']);
        }
        else {
            return response()->json(['error' => 'resource not available']);
        }
    }
}
