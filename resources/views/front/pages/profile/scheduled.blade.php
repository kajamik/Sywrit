@extends('front.layout.app')

@section('main')
<style>
#nav > li {
  display: inline-block;
  margin-top: 5px;
  margin-bottom: 10px;
  font-size: 20px;
}
#nav > li:not(:last-child)::after {
  content: ' |\00a0';
}
#nav > li.active a {
  color: #fff;
}
</style>
  <div class="publisher-home">
    <div class="publisher-header" style="background-image: url({{ Auth::user()->getBackground() }})">
      <div class="container">
        <div class="publisher-logo">
          <div class="row">
            <div class="d-inline">
              <img src="{{ Auth::user()->getAvatar() }}" alt="Logo">
            </div>
            <div class="ml-2 mt-2 info">
              <span>
                {!! Auth::user()->getRealName() !!}
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
    <nav class="publisher-nav">
      <ul id='nav' class="row">
        <li><a href="{{ url(Auth::user()->slug) }}">@lang('label.menu.profile')</a></li>
        <li><a href="{{ url(Auth::user()->slug.'/about') }}">@lang('label.menu.contact')</a></li>
        <li class="active"><a href="{{ url(Auth::user()->slug.'/archive') }}">@lang('label.menu.saved_articles')</a></li>
      </ul>
    </nav>
    <div class="publisher-body">
      <hr/>
        <div class="publisher-content">
          <h1>@lang('label.archive')</h1>
          <div class="col-12">
            <div class="row">
              <div class="col-lg-3 col-md-12 p-3 border">
                <ul>
                  <li><a href="{{ url(Auth::user()->slug. '/archive') }}"><i class="fa fa-sliders-h"></i> Visualizza tutti gli articoli</li></a>
                  <li><a href="{{ url(Auth::user()->slug. '/archive/scheduled') }}"><i class="fa fa-calendar-alt"></i> Articoli Programmati</a></li>
                </ul>
              </div>
              <div class="col-lg-8 col-md-12 py-1 ml-3 border">
                @if($query->count())
                <div class="py-2 col-lg-12">
                  <div class="row" id="articles">
                    @foreach($query->take(12) as $value)
                    <div class="col-lg-4 col-sm-8 col-xs-12">
                      <a href="#">
                        <div class="card">
                          <img class="card-img-top" src="{{ $value->getBackground() }}" alt="Copertina">
                          <div class="card-body">
                            <h5 class="card-title">{{ $value->title }}</h5>
                            <p>Programmato per {{ \Carbon\Carbon::parse($value->scheduled_at)->translatedFormat('l j F Y')  }} {{ \Carbon\Carbon::parse($value->scheduled_at)->format('H:i')  }}</p>
                          </div>
                        </div>
                      </a>
                    </div>
                    @endforeach
                  </div>
                </div>
                @else
                  <p>@lang('label.no_scheduled_articles')</p>
                @endif
              </div>
            </div>
          </div>
        </div>
  </div>
</div>
<script>
  App.insl('articles');
</script>
@endsection
