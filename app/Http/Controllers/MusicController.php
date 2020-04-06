<?php
// $ php artisan make:model Music && php artisan make:controller UserController --resource

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Music;
use Log;

class MusicController extends Controller
{
    public function upload()
    {
        Log::debug('music: upload');
        return view('music.upload');
    }

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
        return redirect('music');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $FROM_ENC = 'ASCII,JIS,UTF-8,EUC-JP,SJIS';

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

                    $music->album = mb_convert_encoding($tag['id3v2']['comments']['album'][0],'UTF-8',$FROM_ENC) ?? '';
                    $music->artist = mb_convert_encoding($tag['id3v2']['comments']['artist'][0],'UTF-8',$FROM_ENC) ?? '';
                    $music->bitrate = $tag['bitrate'] ?? '';
                    $music->cover = '';
                    $music->genre = mb_convert_encoding($tag['id3v2']['comments']['genre'][0],'UTF-8',$FROM_ENC) ?? '';
                    $music->originalArtist = '';
                    $music->playtime_seconds = $tag['playtime_seconds'] ?? '';
                    $music->related_works = '';
                    $music->title = mb_convert_encoding($tag['id3v2']['comments']['title'][0],'UTF-8',$FROM_ENC) ?? '';
                    $music->track_num = $tag['id3v2']['comments']['track_number'][0] ?? '';
                    $music->year = $tag['id3v2']['comments']['recording_time'][0] ?? '';
                    $music->save();
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
        $genre_list = \getid3_id3v1::ArrayOfGenres();

        $music = Music::where('id', $id)->first();
        return view('music.edit', ['music' => $music, 'genre_list' => $genre_list]);
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
        $validatedData = $request->validate([
            // 'title' => 'required|unique:musics|max:255',
            'audio' => 'mimes:jpeg,png|dimensions:min_width=1,min_height=1,max_width=500,max_height=500',
        ]);

        $music = Music::where('id', $id)->first();
        if($music){
            Log::debug('music: ' . print_r($music, true));
            if ($request->hasFile('cover')) {
                $cover = $request->file('cover');
                Log::debug('cover: ' . print_r($cover, true));
                if ($cover->isValid([])) {
                    Log::debug('cover: ' . print_r($cover, true));
                    $stored = basename($cover->store('public/covers'));
                    Log::debug('stored: ' . $stored);

                    $path = $cover->path();
                    Log::debug('path: ' . $path);
                    $music->cover = '/storage/covers/' . $stored;
                }
            }

            $music->title = $request->title ?? '';
            $music->artist = $request->artist ?? '';
            $music->album = $request->album ?? '';
            $music->track_num = $request->track_num ?? '';
            $music->bitrate = $request->bitrate ?? '';
            $music->genre = $request->genre ?? '';
            $music->originalArtist = $request->originalArtist ?? '';
            $music->playtime_seconds = $request->playtime_seconds ?? '';
            $music->related_works = $request->related_works ?? '';
            $music->year = $request->year ?? '';
            $music->save();
            return redirect('music/'.$music->id)->with('success', '更新しました。');
        }else{
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['music' => '更新できませんでした']);
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
        $music = Music::where('id', $id)->first();
        $music->delete();
        return redirect('music');
    }
}
