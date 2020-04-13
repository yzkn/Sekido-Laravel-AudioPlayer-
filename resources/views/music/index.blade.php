@extends('layouts.app')

@section('content')
<link rel="stylesheet" type="text/css" href="/css/audio.css">
<div class="container">
    <div class="row justify-content-center">
        @can('user-higher') {{-- ユーザー権限以上に表示される --}}
            <div class="col-md-8">
                <div class="jumbotron my-5">
                    <h1 class="display-4 my-3">Player</h1>
                    <div class="row mt-3 justify-content-center">
                        <audio autoplay preload="auto"></audio>
                    </div>
                    <div class="row mt-1 col-sm-9 offset-sm-3">
                        <a id="audio_artist" href="#" target="_blank">***</a> &nbsp; / &nbsp;
                        <a id="audio_detail" href="#" target="_blank"><span id="audio_title" href="#" target="_blank">***</span></a>
                    </div>
                    <div class="row mt-1 col-sm-9 offset-sm-3">
                        <button id="add_to_queue" type="button" class="queue btn btn-outline-warning">Add to queue</button>
                    </div>
                </div>

                <ol id="playlist" class="list-group my-5 col-md-12">
                    @foreach ($musics as $music)
                        <li class="list-group-item">
                            <a href="#"
                                data-src="{{ $music->path }}"
                                id="{{ $music->id }}"
                                audio_artist="{{ $music->artist }}"
                                audio_title="{{ $music->title }}" >{{$music->artist}} / {{$music->title}}</a>
                        </li>
                    @endforeach
                </ol>
            </div>
        @endcan

        @can('admin-higher') {{-- 管理者権限以上に表示される --}}
            <div class="col-md-8 my-5">
                <div class="card">
                    <div class="card-header">Admin menu</div>

                        <div class="card-body">
                            <ul class="list-group">
                                <li class="list-group-item"><a href="{{ url('/music/upload') }}">{{ __('Upload') }}</a></li>
                            </ul>
                        </div>
                </div>
            </div>
        @endcan

        <div class="col-md-8 my-10"><br><br><br><br><br><br><br><br><br><br></div>

        <div class="col-md-8" id="shortcuts">
            <div class="row">
                <div class="col">
                    <h1>Keyboard shortcuts:</h1>
                    <p><em>&rarr;</em> Next track</p>
                    <p><em>&larr;</em> Previous track</p>
                    <p><em>Space</em> Play/pause</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="/js/jquery.js"></script>
<script src="/js/audio.js"></script>
<script src="/js/audioapp.js"></script>
@endsection
