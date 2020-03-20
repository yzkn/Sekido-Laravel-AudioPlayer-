<?php
// $ php artisan make:model Music && php artisan make:controller UserController --resource

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Music;
use Log;

class MusicController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $musics = Music::get();
        Log::debug('musics: ' . $musics);
        return view('music.index', ['musics' => $musics]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('music.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            // 'title' => 'required|unique:musics|max:255',
            'audio' => 'mimes:mp3,mpga',
        ]);

        if ($request->hasFile('audios')) {
            foreach ($request->file('audios') as $index=> $audio) {
                if ($audio->isValid()) {
                    Log::debug('audio: ' . print_r($audio, true));
                    $stored = basename($audio->store('public/musics'));
                    Log::debug('stored: ' . $stored);

                    $path = $audio->path();
                    Log::debug('path: ' . $path);

                    $getID3 = new \getID3();
                    $tag = $getID3->analyze($path);

                    $music = new Music;

                    $music->path = '/storage/musics/' . $stored;

                    $music->album = mb_convert_encoding($tag['id3v2']['comments']['album'][0],'UTF-8','auto') ?? '';
                    $music->artist = mb_convert_encoding($tag['id3v2']['comments']['artist'][0],'UTF-8','auto') ?? '';
                    $music->bitrate = $tag['bitrate'] ?? '';
                    $music->genre = mb_convert_encoding($tag['id3v2']['comments']['genre'][0],'UTF-8','auto') ?? '';
                    $music->originalArtist = '';
                    $music->playtime_seconds = $tag['playtime_seconds'] ?? '';
                    $music->related_works = '';
                    $music->title = mb_convert_encoding($tag['id3v2']['comments']['title'][0],'UTF-8','auto') ?? '';
                    $music->track_num = $tag['id3v2']['comments']['track_number'][0] ?? '';
                    $music->year = $tag['id3v2']['comments']['recording_time'][0] ?? '';
                    $music->save();
                    echo 'save';
                }
            }
        }

        return redirect('music');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $music = Music::where('id', $id)->first();
        return view('music.show', ['music' => $music]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $music = Music::where('id', $id)->first();
        return view('music.edit', ['music' => $music]);
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
        $music = Music::where('id', $id)->first();
        $music->title = $request->title;
        $music->save();
        return redirect('music/'.$music->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $music = Music::where('id', $id)->first();
        $music->delete();
        return redirect('music');
    }
}
