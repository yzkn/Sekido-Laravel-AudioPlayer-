@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Detail - {{ $music->id }}</div>

                <div class="card-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-sm-12 row justify-content-center my-5">
                                <audio preload="auto" controlls src="{{ isset($music) ? $music->path : '' }}"></audio>
                            </div>
                            <div class="col-sm-4 my-2">{{ __('Title') }}</div>
                            <div class="col-sm-8 my-2">{{ $music->title }}</div>
                            <div class="col-sm-4 my-2">{{ __('Artist') }}</div>
                            <div class="col-sm-8 my-2">{{ $music->artist }}</div>
                            <div class="col-sm-4 my-2">{{ __('Album') }}</div>
                            <div class="col-sm-8 my-2">{{ $music->album }}</div>
                            <div class="col-sm-4 my-2">{{ __('Track number') }}</div>
                            <div class="col-sm-8 my-2">{{ $music->track_num }}</div>
                            <div class="col-sm-4 my-2">{{ __('Bitrate') }}</div>
                            <div class="col-sm-8 my-2">{{ $music->bitrate }}</div>
                            <div class="col-sm-4 my-2">{{ __('Genre') }}</div>
                            <div class="col-sm-8 my-2">{{ $music->genre }}</div>
                            <div class="col-sm-4 my-2">{{ __('Original Artist') }}</div>
                            <div class="col-sm-8 my-2">{{ $music->originalArtist }}</div>
                            <div class="col-sm-4 my-2">{{ __('Playtime') }}</div>
                            <div class="col-sm-8 my-2">{{ $music->playtime_seconds }} sec</div>
                            <div class="col-sm-4 my-2">{{ __('Related works') }}</div>
                            <div class="col-sm-8 my-2">{{ $music->related_works }}</div>
                            <div class="col-sm-4 my-2">{{ __('Year') }}</div>
                            <div class="col-sm-8 my-2">{{ $music->year }}</div>
                            <div class="col-sm-4 my-2">{{ __('Cover') }}</div>
                            <div class="col-sm-8 my-2">
                                @isset($music)
                                    @isset($music->cover)
                                        <img src="{{ $music->cover }}" alt="cover"><br>
                                    @endisset
                                @endisset
                            </div>
                            <div class="mt-5 col-sm-8 offset-sm-4">
                                <a href="{{ url('music/'.$music->id.'/edit') }}" class="btn btn-primary">{{ __('Edit') }}</a>
                                <form action="{{ url('music/'. $music->id) }}" method="post" style="display:inline;" onSubmit="return window.confirm('削除しますか？')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" name="submit" class="btn btn-danger">{{ __('Delete') }}</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="/js/jquery.js"></script>
<script src="/js/audio.js"></script>
<script>
    audiojs.events.ready(function() {
        var as = audiojs.createAll();
    });
</script>
@endsection
