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
                </div>

                <div class="jumbotron my-5">
                    <h1 class="display-4 my-3">Search</h1>

                        <form method="POST" action="{{ url('music/search') }}" enctype="multipart/form-data" >
                            {{ csrf_field() }}
                            <div class="row">

                                <div class="col-sm-4 my-2">{{ __('Title') }}</div>
                                <div class="col-sm-8 my-2">
                                    <input type="text" class="form-control" id="title" name="title" value="{{(isset($request) && isset($request['title'])) ? $request['title'] : ''}}" placeholder="{{ __('Title') }}">
                                </div>
                                <div class="col-sm-4 my-2">{{ __('Artist') }}</div>
                                <div class="col-sm-8 my-2">
                                    <input type="text" class="form-control" id="artist" name="artist" value="{{(isset($request) && isset($request['artist'])) ? $request['artist'] : ''}}" placeholder="{{ __('Artist') }}">
                                </div>
                                <div class="col-sm-4 my-2">{{ __('Album') }}</div>
                                <div class="col-sm-8 my-2">
                                    <input type="text" class="form-control" id="album" name="album" value="{{(isset($request) && isset($request['album'])) ? $request['album'] : ''}}" placeholder="{{ __('Album') }}">
                                </div>
                                <div class="col-sm-4 my-2">{{ __('Track number') }}</div>
                                <div class="col-sm-8 my-2">
                                    <input type="number" class="form-control" id="track_num" name="track_num" value="{{(isset($request) && isset($request['track_num'])) ? $request['track_num'] : ''}}" placeholder="{{ __('Track number') }}">
                                </div>
                                <div class="col-sm-4 my-2">{{ __('Genre') }}</div>
                                <div class="col-sm-8 my-2">
                                    <input type="text" class="form-control" id="genre" name="genre" list="genre_list" value="{{(isset($request) && isset($request['genre'])) ? $request['genre'] : ''}}" placeholder="{{ __('Genre') }}">
                                    <datalist id="genre_list">
                                        @foreach($genre_list as $index => $name)
                                            <option value="{{ $name }}">{{ $name}} </option>
                                        @endforeach
                                    </datalist>
                                </div>
                                <div class="col-sm-4 my-2">{{ __('Original Artist') }}</div>
                                <div class="col-sm-8 my-2">
                                    <input type="text" class="form-control" id="originalArtist" name="originalArtist" value="{{(isset($request) && isset($request['originalArtist'])) ? $request['originalArtist'] : ''}}" placeholder="{{ __('Original Artist') }}">
                                </div>
                                <div class="col-sm-4 my-2">{{ __('Playtime') }}</div>
                                <div class="col-sm-6 my-2">
                                    <input type="number" class="form-control" id="playtime_seconds_min" name="playtime_seconds_min" value="{{(isset($request) && isset($request['playtime_seconds_min'])) ? $request['playtime_seconds_min'] : ''}}" placeholder="{{ __('Playtime(min)') }}">
                                </div>
                                <div class="col-sm-2 my-2 d-flex align-items-end">{{ __('seconds') }}</div>

                                <div class="col-sm-6 my-2 offset-sm-4">
                                    <input type="number" class="form-control" id="playtime_seconds_max" name="playtime_seconds_max" value="{{(isset($request) && isset($request['playtime_seconds_max'])) ? $request['playtime_seconds_max'] : ''}}" placeholder="{{ __('Playtime(max)') }}">
                                </div>
                                <div class="col-sm-2 my-2 d-flex align-items-end">{{ __('seconds') }}</div>

                                <div class="col-sm-4 my-2">{{ __('Related works') }}</div>
                                <div class="col-sm-8 my-2">
                                    <input type="text" class="form-control" id="related_works" name="related_works" value="{{(isset($request) && isset($request['related_works'])) ? $request['related_works'] : ''}}" placeholder="{{ __('Related works') }}">
                                </div>
                                <div class="col-sm-4 my-2">{{ __('Year') }}</div>
                                <div class="col-sm-8 my-2">
                                    <input type="number" class="form-control" id="num" name="year" value="{{(isset($request) && isset($request['year'])) ? $request['year'] : ''}}" placeholder="{{ __('Year') }}">
                                </div>
                                <div class="mt-5 col-sm-8 offset-sm-4">
                                    <select id="sort_key" name="sort_key">
                                        @foreach($sort_list as $index => $name)
                                            <option value="{{ $name }}" @if(old('sort_list') == $name) selected @endif>{{ $name }}</option>
                                        @endforeach
                                    </select>
                                    <button type="submit" name="submit" class="btn btn-primary">{{ __('Search') }}</a>
                                </div>
                            </div>
                        </form>
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
