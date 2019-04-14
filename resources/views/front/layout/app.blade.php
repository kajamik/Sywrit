<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" prefix="og: http://ogp.me/ns#">
<head>
    <meta charset="utf-8" />
    <title>@yield('title') {{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <meta name="mobile-web-app-capable" content="yes">
    <meta name="theme-color" content="#CC0000">
    @yield('seo')

    <script src="{{ asset('plugins/jquery/js/jquery-3.2.1.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/image.js') }}"></script>

    <link href="{{ asset('plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('plugins/fontawesome/fontawesome.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/app.css') }}" media="all" rel="stylesheet" />
    <link href="{{ asset('css/print.css') }}" media="print" rel="stylesheet" />
    <link href="{{ asset('css/image.css') }}" media="screen" rel="stylesheet" />

    <link href="{{ asset('lib/noty.css') }}" rel="stylesheet">
    <script src="{{ asset('lib/noty.js') }}" type="text/javascript"></script>

    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('upload/rgb_icon.ico') }}" />
    <link rel="icon" href="{{ url('upload/57x57/rgb_logo.png') }}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ url('upload/144x144/rgb_logo.png') }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ url('upload/114x114/rgb_logo.png') }}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ url('upload/72x72/rgb_logo.png') }}">
    <link rel="apple-touch-icon" href="{{ url('upload/57x57/rgb_logo.png') }}">

    <link rel="manifest" href="{{ url('manifest.json') }}">
    <link rel="canonical" href="{{ Request::url() }}" />

    @yield('styles')

    <!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-131300748-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-131300748-1');
</script>

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
    <ul class="footer-links">
      <li><a href="{{ url('page/project') }}">Il nostro progetto</a></li>
      <li><a href="{{ url('page/about/privacy') }}">Privacy</a></li>
      <li><a href="{{ url('page/legal/terms') }}">Termini e condizioni</a></li>
    </ul>
    <p>Sito creato da <a href="{{ url('1-pietropaolocarpentras') }}">Pietro Paolo Carpentras</a>.
    &copy; 2019 - {{ \Carbon\Carbon::now()->format('Y') }}. Tutti i diritti riservati</p>
  </footer>

  <script>
  fetch_live_search = function(query_search = ''){
    App.query("get","{{ route('live_search') }}",{q:query_search},false,function(data){
      $(".data-list").html(data);
    });
  }
  </script>
  @auth
  {{--
    $.each(data.query, function(index, item){
      notify(item.titolo, "Pubblicato da " + item.user_name, "read/"+item.article_slug);
    });
    --}}
  <script type="text/javascript">
    var message_count = 0;

    $(function(){
      //notifications();
    });

    function notifications() {
        App.query("get","{{ url('getStateNotifications') }}",{msg_count:message_count},false,function(data){
            if(data){
              message_count = data.count;
              if($(".badge-notification").length) {
                if(data.count > 0) {
                  $(".badge-notification").text(data.count);
                } else {
                  $(".badge-notification").remove();
                }
              } else {
                $("#notification").append("<span class='badge badge-notification'>"+data.count+"</span>");
              }
          }
            setTimeout(notifications, 2000);
        });
    }

    fetch_live_notifications = function(){
      App.query("get","{{ route('live_notif') }}",null,false,function(data){
          $(".data-notification").html(data);
          $(".badge-notification").remove();
      });
    }
  </script>
  @endauth

</body>
</html>
