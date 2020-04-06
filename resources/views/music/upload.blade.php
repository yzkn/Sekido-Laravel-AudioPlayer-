@extends('layouts.app')

@section('content')
<link rel="stylesheet" type="text/css" href="/css/audio.css">
<div class="container">
    <div class="row justify-content-center">
        @can('admin-higher') {{-- 管理者権限以上に表示される --}}
            <div class="col-md-8">
                <div class="jumbotron mt-4">
                    <h1 class="display-4">Upload</h1>
                    <hr class="my-4">
                    <div class="col">
                        <form method="POST" action="{{ url('music/') }}" enctype="multipart/form-data" >
                            {{ csrf_field() }}
                            <div class="input-group">
                                <label class="input-group-btn">
                                    <span class="btn btn-primary">
                                        Choose File<input type="file" style="display:none" name="audios[]" multiple>
                                    </span>
                                </label>
                                <input type="text" class="form-control" readonly="">
                            </div>
                            <button type="submit" class="btn btn-primary">Upload</button>
                        </form>
                    </div>
                </div>
            </div>
        @endcan
    </div>
</div>
<script src="/js/jquery.js"></script>
<script>
    FileList.prototype.map = function(){
        return Array.prototype.map.call(this, ...arguments)
    }
    $(function() {
        $(document).on('change', ':file', function() {
            var input = $(this),
            numFiles = input.get(0).files ? input.get(0).files.length : 1,
            label = ((input.get(0).files.map(file => (file.name).replace(/\\/g, '/').replace(/.*\//, ''))).join(' , '));
            input.parent().parent().next(':text').val(label + ( numFiles < 2 ? '' : (' ほか 計' + numFiles + 'ファイル')));
        });
    });
</script>
@endsection
