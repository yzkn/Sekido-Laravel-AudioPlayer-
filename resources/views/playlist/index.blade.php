@extends('layouts.app')

@section('content')
<link rel="stylesheet" type="text/css" href="/css/audio.css">
<div class="container">
    <div class="row justify-content-center">
        @can('user-higher') {{-- ユーザー権限以上に表示される --}}
            <div class="col-md-8">
                <div class="jumbotron my-5">
                    <h1 class="display-4 my-3">Playlist</h1>
                    <a href="{{ url('playlist/create') }}" class="btn btn-info">{{ __('Create') }}</a>
                </div>

                <ol id="playlist" class="list-group my-5 col-md-12">
                    @foreach ($playlists as $playlist)
                        <li class="list-group-item">
                            <a href="{{ url('playlist/'. $playlist->id) }}">{{$playlist->title}}</a>
                            <form action="{{ url('playlist/'. $playlist->id) }}" method="post" style="display:inline;" onSubmit="return window.confirm('削除しますか？')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" name="submit" class="btn btn-danger">{{ __('Delete') }}</button>
                            </form>
                        </li>
                    @endforeach
                </ol>
            </div>
        @endcan
@endsection
