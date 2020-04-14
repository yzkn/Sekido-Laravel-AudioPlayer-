@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Detail - {{ $playlist->id ?? '' }}</div>
                <div class="card-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-sm-12 row justify-content-center my-5">
                                <audio preload="auto" controlls src="{{ isset($playlist) ? ($playlist->path ?? '') : '' }}"></audio>
                            </div>
                            <div class="col-sm-4 my-2">{{ __('Title') }}</div>
                            <div class="col-sm-8 my-2">{{ $playlist->title ?? '' }}</div>
                            <div class="col-sm-4 my-2">{{ __('Description') }}</div>
                            <div class="col-sm-8 my-2">{{ $playlist->description ?? '' }}</div>
                            <div class="mt-5 col-sm-8 offset-sm-4">
                                @isset($records)
                                    @isset($playlist->id)
                                        <a href="{{ url('playlist/'.$playlist->id.'/edit') }}" class="btn btn-primary">{{ __('Edit') }}</a>
                                        <form action="{{ url('playlist/'. $playlist->id) }}" method="post" style="display:inline;" onSubmit="return window.confirm('削除しますか？')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" name="submit" class="btn btn-danger">{{ __('Delete') }}</button>
                                        </form>
                                    @endisset
                                @endisset
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
