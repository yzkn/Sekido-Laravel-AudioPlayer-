<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMusicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('musics', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->string('path')->unique();
            $table->string('title');
            $table->string('artist');
            $table->string('album');
            $table->tinyInteger('track_num');
            $table->string('related_works');
        });
    }

    /*
    audiofile.tag.artist = u"Nobunny"
audiofile.tag.album = u"Love Visions"
audiofile.tag.title = u"I Am a Girlfriend"
audiofile.tag.track_num = 4
    */
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('musics');
    }
}
