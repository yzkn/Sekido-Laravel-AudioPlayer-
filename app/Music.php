<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Music extends Model{
    protected $fillable = [
        'album',
        'artist',
        'bitrate',
        'cover',
        'genre',
        'originalArtist',
        'path',
        'playtime_seconds',
        'related_works',
        'title',
        'track_num',
        'year',
    ];

    public function toString(){
        return
            'album: ' . $this -> album . "\t" .
            'artist: ' . $this -> artist . "\t" .
            'bitrate: ' . $this -> bitrate . "\t" .
            'cover: ' . $this -> cover . "\t" .
            'genre: ' . $this -> genre . "\t" .
            'id: ' . $this -> id . "\t" .
            'originalArtist: ' . $this -> originalArtist . "\t" .
            'path: ' . $this -> path . "\t" .
            'playtime_seconds: ' . $this -> playtime_seconds . "\t" .
            'related_works: ' . $this -> related_works. "\t" .
            'title: ' . $this -> title . "\t" .
            'track_num: ' . $this -> track_num . "\t" .
            'year: ' . $this -> year . "\t"
        ;
    }
}
