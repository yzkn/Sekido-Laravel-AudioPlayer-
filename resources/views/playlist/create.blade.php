@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Create</div>
                <div class="card-body">
                    <form action="{{ url('playlist/') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="container">
                            <div class="row">
                                <div class="col-sm-4 my-2">{{ __('Title') }}</div>
                                <div class="col-sm-8 my-2">
                                    <input type="text" class="form-control" id="title" name="title" value="{{ old('title', '') }}" placeholder="{{ __('Title') }}">
                                </div>
                                <div class="col-sm-4 my-2">{{ __('Description') }}</div>
                                <div class="col-sm-8 my-2">
                                    <input type="text" class="form-control" id="description" name="description" value="{{ old('description', '') }}" placeholder="{{ __('Description') }}">
                                </div>
                                <div class="mt-5 col-sm-8 offset-sm-4">
                                    <button type="submit" name="submit" class="btn btn-primary">{{ __('Create') }}</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


{{-- Copyright (c) 2020 YA-androidapp(https://github.com/YA-androidapp) All rights reserved. --}}