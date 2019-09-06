<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Music extends Model{
    protected $fillable = ['path', 'title', 'artist', 'album', 'track_num', 'related_works'];

    public function toString(){
        return 'id: ' . $this -> id . "\t" . 'path: ' . $this -> path . "\t" . 'title: ' . $this -> title . "\t" . 'artist: ' . $this -> artist . "\t" . 'album: ' . $this -> album . "\t" . 'track_num: ' . $this -> track_num . "\t" . 'related_works: ' . $this -> related_works;
    }
}
