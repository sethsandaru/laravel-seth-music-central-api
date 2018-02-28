<?php

namespace App\Http\Controllers;

use App\Genre;
use Illuminate\Http\Request;

class GenreController extends Controller
{
    //

    public function GetAll()
    {
        if (\request()->method() != 'GET')
            return response()->setStatusCode('401')->json(['error', 'method not allowed']);

        return response()->json(Genre::select('genre_id','genre_name')->get());
    }
}
