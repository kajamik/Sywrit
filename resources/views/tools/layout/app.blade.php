<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Pensa, crea, condividi">

    <title>@yield('title') Sywrit</title>

    <!-- Scripts -->
    <script src="{{ asset('js/bootstrap/bootstrap.min.js') }}"></script>
    <script src="{{ asset('plugins/jquery/js/jquery-3.2.1.min.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/_dex.css') }}" rel="stylesheet">
    <link href="{{ asset('css/image.css') }}" media="screen" rel="stylesheet" type="text/css">

    <link rel="icon" href="{{ asset('upload/icons/16x16/sywrit.png') }}" />
    
    <!-- SEO -->
    <link rel="canonical" href="{{ Request::url() }}">

    @yield('styles')
</head>
<body id="__ui">

  <script type="application/ld+json">
  {
      "@context": "http://schema.org",
      "@type": "Nome Sito",
      "url": "{{url('')}}",
      "name": "Nome Sito",
      "description": "Pensa, crea, condividi"
  }
  </script>

  <header id="header">
    @include('tools.components.menu')
  </header>

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
    <p>Sito progettato e sviluppato da Pietro Paolo Carpentras.</p>
  </footer>

  <script type="text/javascript">
  (function(){
    $(".nav-toggler").on("click", function(){$("nav.nav").toggleClass("open");$("body").toggleClass("navbar-open");});
    $(".menu-close").on("click", function() {$("nav.nav").toggleClass("open");$("body").toggleClass("navbar-open");});
  })(jQuery);
  </script>

</body>
</html>
