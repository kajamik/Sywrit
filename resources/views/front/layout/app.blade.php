<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <title>@yield('title') {{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    @yield('seo')

    <script src="{{ asset('plugins/jquery/js/jquery-3.2.1.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/image.js') }}"></script>

    <link href="{{ asset('plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" rel="stylesheet" />
    <link href="{{ asset('css/app.css') }}" media="all" rel="stylesheet" />
    <link href="{{ asset('css/print.css') }}" media="print" rel="stylesheet" />
    <link href="{{ asset('css/image.css') }}" media="screen" rel="stylesheet" />

    <link rel="icon" href="{{ asset('upload/logo_white.PNG') }}" />

    @yield('styles')
</head>
<body id="__ui">

  <header id="header">
    @include('front.components.menu')
  </header>

  <script>
  (function(){
    App.update();
    $(window).on('resize', function(){
      App.update();
    });
  })(jQuery);

  </script>

  <main class="wrap">
    @if(\Session::get('type') == 'main_top')
    <div class="fixed-alert alert alert-danger">
      <h2>{{\Session::get('message')}}</h2>
    </div>
    @endif
    <div class="py-3">
      @yield('main')
    </div>
  </main>

  <footer id="footer">
    <p>Sito creato da <a href="{{ url('paolocarpentras') }}">Pietro Paolo Carpentras</a>.
    <br/>&copy; 2019 - {{ \Carbon\Carbon::now()->format('Y') }}. Tutti i diritti riservati</p>
  </footer>

  <script type="text/javascript">
    fetch_live_search = function(query_search = ''){
      App.query("get","{{ route('live_search') }}",{q:query_search},false,function(data){
        $(".data-list").html(data);
      });
    }
    fetch_live_notifications = function(){
      App.query("get","{{ route('live_notif') }}",null,false,function(data){
        $(".data-notification").html(data);
      });
    }
  </script>

</body>
</html>
