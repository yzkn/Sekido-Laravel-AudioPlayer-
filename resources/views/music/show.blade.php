@extends('layouts.app')

@section('content')
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

                        @can('admin-higher') {{-- 管理者権限以上に表示される --}}
                        System Administrator
                        @endcan
                    </span>
                    {{ $music->id }}
                    {{ $music->path }}
                    {{ $music->title }}
                    {{ $music->artist }}
                    {{ $music->album }}
                    {{ $music->track_num }}
                    {{ $music->related_works }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
