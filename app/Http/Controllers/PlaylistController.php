<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Playlist;

class PlaylistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $playlists = Playlist::where('user_id', Auth::user()->id)->get();
        Log::debug('playlists: ' . $playlists);
        return view('playlist.index', ['playlists' => $playlists]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('playlist.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $playlist = new Playlist;
        $playlist->description = $request->description;
        $playlist->title = $request->title;
        $playlist->user_id = Auth::user()->id;
        $playlist->save();

        return redirect('playlist');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $playlist = Playlist::where('id', $id)->first();
        return view('playlist.show', ['playlist' => $playlist]);
        // return view('music.index', ['musics' => $playlist->musics]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $playlist = Playlist::where('id', $id)->first();
        return view('playlist.edit', ['playlist' => $playlist]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $playlist = Playlist::where('id', $id)->first();
        if($playlist){
            $playlist->title = $request->title ?? '';
            $playlist->description = $request->description ?? '';
        }else{
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['playlist' => '更新できませんでした']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $playlist = Playlist::where('id', $id)->first();
        $playlist->delete();
        return redirect('playlist');
    }
}
