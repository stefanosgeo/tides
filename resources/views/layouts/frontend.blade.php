<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>


    <!-- Styles -->
    <link rel="icon" href="{{ URL::asset('/css/favicon.ico') }}" type="image/x-icon"/>
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    @livewireStyles
</head>
<body class="font-sans antialiased leading-none bg-gray-100 ">
    <div id="app" class="flex flex-col min-h-screen">
        <header class="fixed top-0 z-10 p-2 py-4 mt-0 w-full bg-gray-800 ">
            <nav class="container flex justify-between items-center px-6 mx-auto">
                <div>
                    <a href="{{ url('/') }}" class="text-lg font-semibold text-gray-100 no-underline">
                        {{ config('app.name', 'Laravel') }}
                    </a>
                </div>
                <nav class="space-x-4 text-sm font-semibold text-gray-300 sm:text-base">

                    <span class="no-underline ">
                        <a href="/set_lang/en" class="{{ (session('locale') === 'en')?'underline':'' }}">EN</a> |
                        <a href="/set_lang/de" class="{{ (session('locale') === 'de')?'underline':'' }}">DE</a>
                    </span>
                    @guest
                        <a class="no-underline hover:underline"
                           href="{{ route('login') }}"
                        >{{ __('auth.Login') }}</a>
                        @if (Route::has('register'))
                            <a class="no-underline hover:underline"
                               href="{{ route('register') }}"
                            >{{ __('auth.Register') }}</a>
                        @endif
                    @else

                        @if(!str_contains(url()->current(), 'admin'))
                           <a href="/admin/dashboard"
                              class="no-underline hover:underline"
                           > Dashboard </a>
                        @endif
                        <span>{{ Auth::user()->name }}</span>

                        <a href="{{ route('logout') }}"
                           class="no-underline hover:underline"
                           onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();"
                        >{{ __('Logout') }}</a>
                        <form id="logout-form"
                              action="{{ route('logout') }}"
                              method="POST"
                              class="hidden">
                            {{ csrf_field() }}
                        </form>
                    @endguest
                </nav>
            </nav>
        </header>

        <main class="mb-auto flex-grow">
            @yield('content')
        </main>

        <footer class="flex bg-gray-800 h-10 mt-6 justify-center items-center">
            <div class="space-x-4 text-sm text-gray-300 sm:text-base">
                Copyright @ {{ Illuminate\Support\Carbon::now()->year }} MIT Licence
            </div>
        </footer>
    </div>
    <script src="{{ mix('js/app.js') }}" ></script>
</body>
</html>
