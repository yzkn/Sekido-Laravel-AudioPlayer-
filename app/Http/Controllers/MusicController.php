<?php
// $ php artisan make:model Music && php artisan make:controller UserController --resource

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Music;
use App\Playlist;

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
    public function index(Request $request)
    {
        $user = Auth::user();
        $token = auth('api')->login($user);
        Log::debug('token: ' . $token);

        $musics = Music::get();
        Log::debug('musics: ' . $musics);
        // return view('music.index', ['musics' => $musics]);
        return response()
            ->view('music.index', ['musics' => $musics])
            ->cookie('jwttoken', $token, 30);
    }

    public function search(Request $request)
    {
        Log::debug(get_class($this).' '.__FUNCTION__.'()');
        Log::debug('User: '.Auth::user());
        Log::debug('$request: '.$request);

        $genre_list = \getid3_id3v1::ArrayOfGenres();
        $sort_list = [
            'album','artist','created_at','genre','originalArtist','related_works','title','year','track_num','playtime_seconds','-album','-artist','-created_at','-genre','-originalArtist','-related_works','-title','-year','-track_num','-playtime_seconds'
        ];

        $query = Music::query();
        // 'playtime_seconds_min', 'playtime_seconds_max'は別途
        foreach ($request->only(['album','artist','cover','created_at','genre','originalArtist','related_works','title','year','track_num']) as $key => $value) {
            if(($request->get($key))){
                $query->where($key, 'like', '%'.$value.'%');
            }
        }

        if($request->has('playtime_seconds_min') && ($request->get('playtime_seconds_min'))) {
            Log::debug('playtime_seconds_min: '.$request->get('playtime_seconds_min'));
            $query->where('playtime_seconds', '>=', $request->get('playtime_seconds_min'));
        }
        if($request->has('playtime_seconds_max') && ($request->get('playtime_seconds_min'))) {
            $query->where('playtime_seconds', '<=', $request->get('playtime_seconds_max'));
        }

        if($request->has('sort_key') && ($request->get('sort_key'))) {
            if(
                in_array(
                    $request->get('sort_key'),
                    [
                        'album','artist','created_at','genre','originalArtist','related_works','title','year','track_num','playtime_seconds',
                        '-album','-artist','-created_at','-genre','-originalArtist','-related_works','-title','-year','-track_num','-playtime_seconds'
                    ]
                )
            ) {
            Log::debug('sort_key: '.$request->sort_key);
                $query->orderBy($request->sort_key, (strpos($request->sort_key, '-') === 0 ) ? 'desc' : 'asc');
            }
        }

        $musics = $query->get();

        Log::debug('request: '.print_r($request->only(['album','artist','created_at','genre','originalArtist','related_works','title','year','track_num','playtime_seconds_min','playtime_seconds_max','sort_key']), true));

        return view('music.search', ['musics' => $musics, 'genre_list' => $genre_list, 'sort_list' => $sort_list, 'request' => $request->only(['album','artist','created_at','genre','originalArtist','related_works','title','year','track_num','playtime_seconds_min','playtime_seconds_max','sort_key'])]);
    }

    public function searchform()
    {
        Log::debug(get_class($this).' '.__FUNCTION__.'()');
        Log::debug('User: '.Auth::user());

        $genre_list = \getid3_id3v1::ArrayOfGenres();
        $sort_list = [
            'album','artist','created_at','genre','originalArtist','related_works','title','year','track_num','playtime_seconds',
            '-album','-artist','-created_at','-genre','-originalArtist','-related_works','-title','-year','-track_num','-playtime_seconds'
        ];

        return view('music.search', ['musics' => array(), 'genre_list' => $genre_list, 'sort_list' => $sort_list]);
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
            'audios.*' => 'mimes:mp3,mpga,jpeg,png',
        ]);

        if ($request->hasFile('audios')) {
            $image_path = '';
            foreach ($request->file('audios') as $index=> $audio) {
                if ($audio->isValid()) {
                    if(strpos($audio->getMimeType(), 'image') === 0){
                        $stored = basename($audio->store('public/covers'));
                        Log::debug('stored: ' . $stored);
                        $image_path = '/storage/covers/' . $stored;
                        break;
                    }
                }
            }

            foreach ($request->file('audios') as $index=> $audio) {
                if ($audio->isValid()) {
                    Log::debug('audio: ' . print_r($audio, true));
                    $stored = basename($audio->store('public/musics'));
                    Log::debug('stored: ' . $stored);

                    if(strpos($audio->getMimeType(), 'audio') === 0){
                        Log::debug('path: ' . $audio->path());

                        $getID3 = new \getID3();
                        $tag = $getID3->analyze($audio->path());

                        $music = new Music;

                        $music->path = '/storage/musics/' . $stored;

                        $music->album = mb_convert_encoding($tag['id3v2']['comments']['album'][0],'UTF-8',$FROM_ENC) ?? '';
                        $music->artist = mb_convert_encoding($tag['id3v2']['comments']['artist'][0],'UTF-8',$FROM_ENC) ?? '';
                        $music->bitrate = $tag['bitrate'] ?? '';
                        $music->cover = $image_path ?? '';
                        $music->genre = mb_convert_encoding($tag['id3v2']['comments']['genre'][0],'UTF-8',$FROM_ENC) ?? '';
                        $music->originalArtist = '';
                        $music->playtime_seconds = $tag['playtime_seconds'] ?? '';
                        $music->related_works = '';
                        $music->title = mb_convert_encoding($tag['id3v2']['comments']['title'][0],'UTF-8',$FROM_ENC) ?? '';
                        $music->track_num = $tag['id3v2']['comments']['track_number'][0] ?? '';
                        $music->year = $tag['id3v2']['comments']['recording_time'][0] ?? '';
                        $music->save();

                        $music_array[] = $music->id;
                    }
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

        $playlists = Playlist::where('user_id', Auth::user()->id)->get();
        Log::debug('playlists: ' . $playlists);

        $music = Music::where('id', $id)->first();
        $music_playlists = array();
        foreach ($music->playlists as $value) {
            $music_playlists[] = $value->id;
        }
        Log::debug('music_playlists: ' . print_r($music_playlists, true));
        return view('music.edit', ['music' => $music, 'genre_list' => $genre_list, 'playlists' => $playlists, 'music_playlists' => $music_playlists]);
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



            $playlists = $request->playlists ?? [];
            unset($request['playlists']);
            if(count($playlists)>0){
                Log::debug('id: ' . print_r($id, true));
                // Log::debug('playlists: ' . print_r($playlists, true));
                // Log::debug('music->playlists: ' . print_r($music->playlists, true));

                if(0 === count($playlists)){
                    $new_playlist = array();
                }else{
                    $new_playlist = (array)$playlists;
                }

                if(0 === count($music->playlists)){
                    $old_playlist = array();
                } else {
                    foreach ($music->playlists as $value) {
                        $old_playlist[] = $value->id;
                    }
                }

                Log::debug('old_playlist: ' . print_r($old_playlist, true));
                Log::debug('new_playlist: ' . print_r($new_playlist, true));

                $add = array_diff($new_playlist, $old_playlist);
                $remove = array_diff($old_playlist, $new_playlist);
                Log::debug('add: ' . print_r($add, true));
                Log::debug('remove: ' . print_r($remove, true));

                $music->savePlaylists($id, $add, $remove);
            }

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
