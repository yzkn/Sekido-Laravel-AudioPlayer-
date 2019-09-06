<?php
// $ php artisan make:model Music && php artisan make:controller UserController --resource

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Music;

class MusicController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $musics = Music::latest()->get();
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
        echo 'store';
        $validatedData = $request->validate([
            // 'title' => 'required|unique:musics|max:255',
            'audio' => 'mimes:mp3,mpga',
        ]);
        echo 'validatedData';

        if ($request->hasFile('audio')) { //"audio" は input type の name属性
            echo 'audio';

            if ($request->file('audio')->isValid()) { //"audio" は input type の name属性
                echo 'isValid';
                echo $request->file('audio')->guessExtension();

                $path = $request->file('audio')->store('musics');
            }
        }
        echo 'endif';

        $music = new Music;
        $music->path = $path;
        $music->title = 't'; // $request->title;
        $music->artist = 't';
        $music->album = 't';
        $music->track_num = 1;
        $music->related_works = 't';
        $music->save();
        echo 'save';


        // hash_hmac('sha256', $pass, false)

        return redirect('music/'.$music->id);
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
