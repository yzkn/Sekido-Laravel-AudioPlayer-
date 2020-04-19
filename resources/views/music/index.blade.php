@extends('layouts.app')

@section('content')
<link rel="stylesheet" type="text/css" href="/css/audio.css">
<link rel="stylesheet" type="text/css" href="/css/marquee.css">
<div class="container">
    <div class="row justify-content-center">
        @can('user-higher') {{-- ユーザー権限以上に表示される --}}
            <div class="col-md-12">
                <div class="jumbotron my-5">
                    <h1 class="display-4 my-3">Player</h1>
                    <hr class="my-4">
                    <div class="row mt-3 justify-content-center">
                        <audio autoplay preload="auto"></audio>
                    </div>
                    <div class="row mt-1 col-sm-9 offset-sm-3">
                        <div class="marquee">
                            <p>
                                <a id="audio_artist" href="#" target="_blank">***</a> &nbsp; / &nbsp;
                                <a id="audio_detail" href="#" target="_blank"><span id="audio_title" href="#" target="_blank">***</span></a>
                            </p>
                        </div>
                    </div>
                </div>

                <ol id="playlist" class="list-group my-5 col-md-12">
                    @foreach ($musics as $music)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <a href="#"
                                class="musicitem"
                                data-src="{{ $music->path }}"
                                id="{{ $music->id }}"
                                audio_artist="{{ $music->artist }}"
                                audio_title="{{ $music->title }}" >
                                <img src="{{ $music->cover }}" class="img-thumbnail music-item-thumbnail" style="{{ $music->cover ? '' : 'visibility:hidden'}}">
                                    {{$music->artist}} / {{$music->title}}
                            </a>
                            <span>
                                <button type="button" class="queue btn btn-sm btn-outline-warning">Add to queue</button>
                                <a role="button" href="/music/{{ $music->id }}" class="detail btn btn-sm btn-outline-info" target="_blank">Detail</a>
                            </span>
                        </li>
                    @endforeach
                </ol>
            </div>
        @endcan
    </div>
</div>

<script src="/js/jquery.js"></script>
<script src="/js/audio.js"></script>
<script src="/js/audioapp.js"></script>
@endsection
