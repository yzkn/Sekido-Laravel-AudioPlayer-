@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        @can('user-higher') {{-- ユーザー権限以上に表示される --}}
            <div class="col-md-12">
                <div class="jumbotron my-5">
                    <h1 class="display-4 my-3">List</h1>
                    <hr class="my-4">
                    <p class="lead text-right mt-4">
                        {{ isset($column) ? ( __($column).'に含まれるデータの一覧を表示しています。' ) : '' }}
                    </p>
                </div>

                <ol id="list" class="list-group my-5 col-md-12">
                    @foreach ($list_items as $key => $list_item)
                        <li class="list-group-item">
                            <a href="{{ url('music/search').'?'.$column.'='.$list_item->$column }}">{{$list_item->$column}}</a>


                            <a role="button" href="#" onclick="event.preventDefault();document.getElementById('music-search-column-form-{{ $key }}').submit();">
                                {{ $column }}
                            </a>
                            <form id="music-search-column-form-{{ $key }}" action="{{ url('music/search') }}" method="POST"
                                style="display: none;">
                                @csrf
                                <input type="hidden" name="{{ $column }}" value="{{ $list_item->$column }}">
                            </form>


                            <span class="badge badge-info badge-pill ml-1 mr-5">{{ $list_item->count }}</span>
                        </li>
                    @endforeach
                </ol>
            </div>
        @endcan
@endsection
