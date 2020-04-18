<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/audioapp.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ __('Music') }} <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="nav-link" href="{{ url('music') }}">{{ __('List') }}</a></li>
                                    <li><a class="nav-link" href="{{ url('music/search') }}">{{ __('Search') }}</a></li>
                                    <li><a class="nav-link" href="{{ url('music/upload') }}">{{ __('Upload') }}</a></li>
                                </ul>
                            </li>

                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ __('Playlist') }} <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="nav-link" href="{{ url('playlist') }}">{{ __('List') }}</a></li>
                                    <li><a class="nav-link" href="{{ url('playlist/create') }}">{{ __('Create') }}</a></li>
                                </ul>
                            </li>

                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            </div>
        @endif

        <div class="row">
            <div class="col-md-8 main">
                <main class="py-4">
                    @yield('content')
                </main>
            </div>
            @can('user-higher') {{-- ユーザー権限以上に表示される --}}
                <aside class="col-md-2 py-4 sidebar">
                    <div class="p-4 my-5">
                        <h4 class="font-italic">概要</h4>
                        <p class="mb-0">この文章はダミーです。文字の大きさ、量、字間、行間等を確認するために入れています。</p>
                    </div>

                    <div class="p-4 mb-3">
                        <h4>曲</h4>

                        <h5>カバーアート</h5>
                        <div class="cover-cloud mb-3">
                            @foreach (App\Music::select('cover')->groupBy('cover')->having('cover', '<>', '')->get() as $key => $music)
                                <div class="cover-cloud-item" style="display:inline;">
                                    <a href="#" onclick="event.preventDefault();document.getElementById('music-search-cover-form-{{ $key }}').submit();">
                                        <img src="{{ $music->cover }}" class="img-thumbnail music-item-thumbnail">
                                    </a>
                                    <form id="music-search-cover-form-{{ $key }}" action="{{ url('music/search') }}" method="POST"
                                        style="display: none;">
                                        @csrf
                                        <input type="hidden" name="cover" value="{{ $music->cover }}">
                                    </form>
                                </div>
                            @endforeach
                        </div>

                        <h5>最近追加</h5>
                        <ul class="list-group mb-3">
                            @foreach (App\Music::latest()->limit(5)->get() as $music)
                                <li class="list-group-item"><a href="{{ url('music/'.$music->id) }}">{{ $music->title }}</a></li>
                            @endforeach
                        </ul>

                        <h5>登録年月</h5>
                        <ul class="list-group mb-3">
                            @for ($i = 0; $i > -6; $i--)
                                <li class="list-group-item">
                                    <a href="#" onclick="event.preventDefault();document.getElementById('music-search-createdat-form-{{ $i }}').submit();">
                                        {{ (new DateTime())->modify($i.' month')->format('Y年m月') }}
                                    </a>
                                    <form id="music-search-createdat-form-{{ $i }}" action="{{ url('music/search') }}" method="POST"
                                        style="display: none;">
                                        @csrf
                                        <input type="hidden" name="created_at" value="{{ (new DateTime())->modify($i.' month')->format('Y-m') }}">
                                    </form>
                                </li>
                            @endfor
                        </ul>
                    </div>
                    <div class="p-4 mb-3">
                        <h4>プレイリスト</h4>
                        <h5>最近追加</h5>
                        <ul class="list-group mb-3">
                            @foreach (App\Playlist::latest()->limit(3)->get() as $playlist)
                                <li class="list-group-item"><a href="{{ url('playlist/'.$playlist->id) }}">{{ $playlist->title }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </aside>
            @endcan
        </div>
    </div>

</body>
</html>
