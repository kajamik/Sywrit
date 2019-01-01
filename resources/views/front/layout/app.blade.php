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
    <script src="{{ asset('plugins/jquery/js/jquery-3.2.1.min.js') }}"></script>
    <script src='https://www.google.com/recaptcha/api.js?render=6LfJtoUUAAAAAFXXRZCr8envuNSo9iZyb3r6tO6c'></script>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
<script type="text/javascript">
$(".nav-toggler").on("click", function(){$("nav.nav").toggleClass("open");$("body").toggleClass("navbar-open");});
$(".menu-close").on("click", function() {$("nav.nav").toggleClass("open");$("body").toggleClass("navbar-open");});
</script>

  <header id="header">
    @include('front.components.menu')
  </header>

  <main class="wrap">
      @yield('main')
  </main>

  <footer>
  </footer>

</body>
</html>
