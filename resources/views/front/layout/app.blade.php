<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" prefix="og: https://ogp.me/ns#">
<head>
    <meta charset="utf-8" />
    <title>{!! SEOMeta::getTitle() !!}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="description" content="{!! SEOMeta::getDescription() !!}" />
    <meta name="theme-color" content="#BFB8EB" />

    <meta property="fb:app_id" content="1180591382121261" />
    <meta property="fb:pages" content="2268752223388216" />

    <script src="{{ asset('js/__dfg.js') }}"></script>

    <link href="{{ asset('plugins/fontawesome/fontawesome.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/app.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('css/print.min.css')}}" media="print" rel="stylesheet"/>
    @yield('css')

    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('upload/rgb_icon.ico') }}" />
    <link rel="icon" href="{{ url('upload/57x57/rgb_logo.png') }}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ url('upload/144x144/rgb_logo.png') }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ url('upload/114x114/rgb_logo.png') }}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ url('upload/72x72/rgb_logo.png') }}">
    <link rel="apple-touch-icon" href="{{ url('upload/57x57/rgb_logo.png') }}">

    <link rel="stylesheet" href="{{ asset('css/image.css') }}" />

    <link rel="manifest" href="{{ url('manifest.json') }}">
    <link rel="canonical" href="{!! SEOMeta::getCanonical() !!}" />

    {!! OpenGraph::generate() !!}

    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>

    {{-- @if(Cookie::get('cookie_consent'))
    <script>(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');ga('create', 'UA-131300748-2', 'auto');ga('send', 'pageview');
    </script>
    @else
    <script>(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');ga('create', 'UA-131300748-2', 'auto');ga('set', 'anonymizeIp', true);ga('send', 'pageview');
    </script>
    @endif --}}

</head>
<body id="__ui">

<div class="sw-site">

  <div class="sw-master">
    <div class="sw-master-app">
      <div class="sw-master-content">
        <div class="sw-master-nav">
          @include('front.components.menu')
        </div>
      </div>
       @include('cookieConsent::index')
    </div>
  </div>

  <div class="sw-content">
    <div class="container fix-container">
      @yield('main')
    </div>
  </div>

  <div class="sw-ft">
    <div class="links d-inline">
      <a href="{{ url('page/about/privacy') }}">@lang('label.footer.privacy')</a>
      <a href="{{ url('page/legal/terms') }}">@lang('label.footer.terms')</a>
      <a href="{{ url('page/about') }}">@lang('Informazioni')</a>
    </div>
    <p>&copy; {{ \Carbon\Carbon::now()->format('Y') }} Sywrit</p>
  </div>

  @yield('js')

  <script>$(function(){App.update();$(window).on('resize', function(){App.update();});App.info();});
  fetch_live_search = function(query_search){App.query("get","{{ route('live_search') }}",{q:query_search},false,function(data){$(".data-list").html(data);});}</script>

  @auth
    {{-- $.each(data.query, function(index, item){
      notify(item.titolo, "{{ __('label.article.published_by') }} " + item.user_name, "read/"+item.article_slug);
    }); --}}

  <script type="text/javascript">
    var message_count = 0;
    $(function(){
      notifications();
      $("#notifications").click(function(){
        fetch_live_notifications();
      });
    });
    function notifications() {
        $.get("{{ url('ajax/notifications') }}", function(a) {
            if(a.unseen_notification > 0) {
              $(".badge-notification").text(a.unseen_notification);
                /*$.each(a.f,  function(index, item) {
                  notify("{{ env('APP_NAME', 'Sywrit') }}", "Leggi l'articolo di " + item.user_name + ": "+ item.article_title, "read/"+item.article_slug, item.article_img);
                });*/
              }
          });
    }
    fetch_live_notifications = function(){
      App.query("get","{{ route('live_notif') }}",null,false,function(data){
          $(".data-notification").html(data);
          $(".badge-notification").remove();
      });
    }
    $(document).on('mouseenter', 'a.thumbnail', function() {
        var $this = $(this);
        var $position;
        $("*").find(".info-box").remove();
        $.get($this.data("card-url"), function(data) {
          $this.after("<div class='info-box'>"+data+"</div>");
          if($(".info-box").height() < ($this.offset().top - $(document).scrollTop())) {
            $position = $this.position().top - $(".info-box").height() - 20;
          } else {
            $position = $this.position().top + 30;
          }
          $(".info-box").css('top', $position + "px");
        });
    });
    $(document).on('mouseenter', '*', function() {
        $(".info-box").remove();
    });
  </script>
  @endauth

</div>
</body>
</html>
