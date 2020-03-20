@extends('layouts.app')

@section('content')
<link rel="stylesheet" type="text/css" href="/css/audio.css">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    You are logged in as a
                    <span>
                        @can('system-only') {{-- システム管理者権限のみに表示される --}}
                        System Administrator
                        @endcan

                    </span>
                </div>
            </div>
        </div>

        @can('admin-higher') {{-- 管理者権限以上に表示される --}}
            <div class="col-md-8">
                <div class="jumbotron mt-4">
                    <h1 class="display-4">Player</h1>
                    <hr class="my-4">
                    <audio autoplay preload="auto"></audio>
                </div>

                <ol id="playlist" class="list-group mt-4 col-md-12">
                    @foreach ($musics as $music)
                        <li class="list-group-item"><a href="#" data-src="{{ $music->path }}" target="blank_">{{$music->artist}} / {{$music->title}}</a></li>
                    @endforeach
                </ol>
            </div>
        @endcan

    </div>
    <div class="col-md-8" id="shortcuts">
        <div class="row">
            @can('admin-higher') {{-- 管理者権限以上に表示される --}}
                <div class="col">
                    <form method="POST" action="/music" enctype="multipart/form-data" >
                        {{ csrf_field() }}
                        <input type="file" name="audios[]" multiple>
                        <input type="submit">
                    </form>
                </div>
            @endcan
            <div class="col">
                <h1>Keyboard shortcuts:</h1>
                <p><em>&rarr;</em> Next track</p>
                <p><em>&larr;</em> Previous track</p>
                <p><em>Space</em> Play/pause</p>
            </div>
        </div>
    </div>
</div>

<script src="/js/jquery.js"></script>
<script src="/js/audio.js"></script>
<script>
    $(function() {
        // Setup the player to autoplay the next track
        var a = audiojs.createAll({
            trackEnded: function() {
            var next = $('ol li.playing').next();
            if (!next.length) next = $('ol li').first();
            next.addClass('playing').siblings().removeClass('playing');
            audio.load($('a', next).attr('data-src'));
            audio.play();
            }
        });

        // Load in the first track
        var audio = a[0];
            first = $('ol a').attr('data-src');
        $('ol li').first().addClass('playing');
        audio.load(first);

        // Load in a track on click
        $('ol li').click(function(e) {
            e.preventDefault();
            $(this).addClass('playing').siblings().removeClass('playing');
            audio.load($('a', this).attr('data-src'));
            audio.play();
        });
        // Keyboard shortcuts
        $(document).keydown(function(e) {
            var unicode = e.charCode ? e.charCode : e.keyCode;
                // right arrow
            if (unicode == 39) {
            var next = $('li.playing').next();
            if (!next.length) next = $('ol li').first();
            next.click();
            // back arrow
            } else if (unicode == 37) {
            var prev = $('li.playing').prev();
            if (!prev.length) prev = $('ol li').last();
            prev.click();
            // spacebar
            } else if (unicode == 32) {
            audio.playPause();
            }
        })
    });
</script>
@endsection
