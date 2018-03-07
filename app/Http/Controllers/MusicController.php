<?php

namespace App\Http\Controllers;

use App\Music;
use Illuminate\Http\Request;

class MusicController extends Controller
{
    //
    public function GetHomePageData()
    {
        if (\request()->method() != 'GET')
            return response()->setStatusCode('401')->json(['error', 'method not allowed']);

        // get time of this week
        $start_week = strtotime('00:00:00 monday this week');
        $end_week = strtotime('23:59:59 sunday this week');

        // query
        $listMusics = Music::whereBetween('created_at', [$start_week, $end_week])
            ->orderBy('total_view', 'DESC')
            ->limit(10)
            ->get();

        // newest
        $newest = Music::orderBy('created_at', 'DESC')
                    ->limit(10)
                    ->get();

        // all time greate
        $alltime = Music::orderBy('total_view', 'DESC')
            ->limit(10)
            ->get();

        return response()->json([
            'weekly' => $listMusics,
            'newest' => $newest,
            'alltime' => $alltime
        ]);
    }

    // search music
    public function Search()
    {
        if (\request()->method() != 'GET')
            return response()->setStatusCode('401')->json(['error', 'method not allowed']);

        $rq = \request();
        $keyword = '';
        if ($rq->has('keyword'))
            $keyword = $rq->get('keyword');

        $id_genre = 0;
        if ($rq->has('genre_id'))
            $id_genre = $rq->get('genre_id');

        $start = $rq->has('start') ? $rq->get('start') : 0;
        $limit = $rq->has('limit') ? $rq->get('limit') : 10;

        // find music
        $musics = null;

        if ($keyword != '' && $id_genre != 0)
        {
            $musics = Music::where([
                ['music_name', 'like', "%$keyword%"],
                ['genre_id', $id_genre]
            ])
                ->skip($start)
                ->take($limit);
        }
        else if ($keyword == '' && $id_genre != 0) {
            $musics = Music::where('genre_id', $id_genre)
                ->skip($start)
                ->take($limit);
        }
        else {
            $musics = Music::where('music_name', 'like', "%$keyword%")
                ->skip($start)
                ->take($limit);
        }

        $count = $musics->count();
        $musics = $musics->orderBy('created_at', 'DESC')
                    ->get();

        return response()->json([
            'musics' => $musics,
            'total'  => $count
        ]);
    }

    /*
     * Get hottest music with pagination
     */
    public function HottestWithPagination($genre_id)
    {
        if (\request()->method() != 'GET')
            return response()->setStatusCode('401')->json(['error', 'method not allowed']);

        $rq = \request();
        $start = $rq->has('start') ? $rq->get('start') : 0;
        $limit = $rq->has('limit') ? $rq->get('limit') : 10;

        $musics = Music::where('genre_id', $genre_id)
                            ->orderBy('total_view', 'DESC')
                            ->skip($start)
                            ->take($limit);

        return response()->json([
            'musics' => $musics->get(),
            'total'  => Music::where('genre_id', $genre_id)->count()
        ]);
    }

    /*
     * Get Music by ID
     */
    public function GetByID($id)
    {
        if (\request()->method() != 'GET')
            return response()->setStatusCode('401')->json(['error', 'method not allowed']);
        
        return response()->json(Music::find($id));
    }

    /*
     *
     */
    public function AddNewView($music_id)
    {
        $music = Music::find($music_id);

        if ($music != null)
        {
            $music->total_view++;
            $music->save();
            return response()->json(['success' => 'ok']);
        }
        else {
            return response()->json(['error' => 'failed']);
        }
    }

    /*
     * Redirect to music file
     */
    public function MusicFile($music_name)
    {
        return redirect("/upload/musics/" . $music_name);
    }
}
