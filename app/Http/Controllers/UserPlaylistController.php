<?php

namespace App\Http\Controllers;

use App\UserPlaylist;
use Illuminate\Http\Request;

class UserPlaylistController extends Controller
{
    /**
     * Get user playlists
     * @param $user_id
     */
    public function GetUserPlaylist($user_id)
    {
        return response()->json(
            UserPlaylist::where('user_id', $user_id)->get()
        );
    }
}
