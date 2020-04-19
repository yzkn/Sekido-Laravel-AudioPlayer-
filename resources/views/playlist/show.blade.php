@extends('layouts.app')

@section('content')
<link rel="stylesheet" type="text/css" href="{{ asset('css/audio.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('css/marquee.css') }}">
<div class="container">
    <div class="row justify-content-center">
        @can('user-higher') {{-- ユーザー権限以上に表示される --}}
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Detail - {{ $playlist->id ?? '' }}</div>
                    <div class="card-body">
                        <div class="container">
                            <div class="row">
                                <div class="col-sm-4 my-2">{{ __('Title') }}</div>
                                <div class="col-sm-8 my-2">{{ $playlist->title ?? '' }}</div>
                                <div class="col-sm-4 my-2">{{ __('Description') }}</div>
                                <div class="col-sm-8 my-2">{{ $playlist->description ?? '' }}</div>
                                <div class="col-sm-4 my-2">{{ __('Cover') }}</div>
                                <div class="col-sm-8 my-2">
                                    @isset($playlist)
                                        @isset($playlist->cover)
                                            @if('' !== $playlist->cover)
                                                <img src="{{ $playlist->cover }}" alt="" class="img-thumbnail">
                                            @endif
                                        @endisset
                                    @endisset
                                </div>
                                <div class="mt-5 col-sm-8 offset-sm-4">
                                    @isset($playlist)
                                        @isset($playlist->id)
                                            <a href="{{ url('playlist/'.$playlist->id.'/edit') }}" class="btn btn-outline-success">{{ __('Edit') }}</a>
                                            <form action="{{ url('playlist/'. $playlist->id) }}" method="post" style="display:inline;" onSubmit="return window.confirm('削除しますか？')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" name="submit" class="btn btn-outline-danger">{{ __('Delete') }}</button>
                                            </form>
                                        @endisset
                                    @endisset
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="jumbotron my-5">
                    <h1 class="display-4 my-3">Player</h1>
                    <div class="row mt-3 justify-content-center">
                        <audio autoplay preload="auto"></audio>
                    </div>
                    <div class="row mt-1 col-sm-9 offset-sm-3">
                        <a id="audio_artist" href="#" target="_blank">***</a> &nbsp; / &nbsp;
                        <a id="audio_detail" href="#" target="_blank"><span id="audio_title" href="#" target="_blank">***</span></a>
                    </div>
                </div>

                <ol id="playlist" class="list-group my-5 col-md-12">
                    @foreach ($playlist->musics as $music)
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
                                <!-- <button type="button" class="queue btn btn-sm btn-outline-warning">Add to queue</button> -->
                                <a role="button" href="/music/{{ $music->id }}" class="detail btn btn-sm btn-outline-info" target="_blank">Detail</a>
                            </span>
                        </li>
                    @endforeach
                </ol>
            </div>
        @endcan
    </div>
</div>

<script src="{{ asset('js/jquery.js') }}"></script>
<script src="{{ asset('js/audio.js') }}"></script>
<script src="{{ asset('js/audioapp.js') }}"></script>
@endsection
