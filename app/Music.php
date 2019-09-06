<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Music extends Model{
    public function toString(){
        return 'id: ' . $this -> id . "\t" . 'path: ' . $this -> path . "\t" . 'title: ' . $this -> title . "\t" . 'artist: ' . $this -> artist . "\t" . 'album: ' . $this -> album . "\t" . 'track_num: ' . $this -> track_num . "\t" . 'related_works: ' . $this -> related_works;
    }
}
