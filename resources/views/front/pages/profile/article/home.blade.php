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
        <li class="active"><a href="{{ url('articles') }}">@lang('label.menu.saved_articles')</a></li>
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
                  <li><i class="fa fa-newspaper"></i> <a class="text-underline" href="{{ url('articles') }}">I miei articoli</li></a>
                  <li><i class="fa fa-calendar-alt"></i> <a href="{{ url('articles/schedule') }}">Articoli programmati</a></li>
                  <li><i class="fa fa-archive"></i> <a href="{{ url('articles/drafts') }}">Articoli salvati</li></a>
                </ul>
              </div>
              <div class="col-lg-8 col-md-12 py-1 ml-lg-3 border">
                @if($query->count())
                <div class="py-2 col-lg-12">
                  <div class="row">
                    <div class="col-12">
                      <div class="row">
                        <div class="col-lg-6 col-md-9">
                          Titolo Articolo
                        </div>
                        <div class="col-3">
                          Pubblicato il
                        </div>
                        {{--
                        <div class="col-lg-3 d-none d-lg-block">
                          Visualizzazioni
                        </div>
                        --}}
                      </div>
                    </div>
                    <div class="mt-4">
                      @foreach($query as $value)
                      <div class="col-12 mt-2">
                        <div class="row">
                          <div class="col-lg-6 col-md-9">
                            <a href="{{ url('read/'. $value->slug) }}">
                              <div class="row">
                                <div class="col-4">
                                  <img class="card-img-top" src="{{ $value->getBackground() }}" alt="Copertina">
                                </div>
                                <div class="col-8">
                                  <strong>{{ $value->titolo }}</strong>
                                </div>
                              </div>
                            </a>
                          </div>
                          <div class="col-lg-4 col-md-3">
                            {{ \Carbon\Carbon::parse($value->created_at)->format('d/m/Y H:i') }}
                          </div>
                          {{--
                          <div class="col-lg-2 d-none d-lg-block">
                            {{ $value->getViewCounts() }}
                          </div>
                          --}}
                        </div>
                      </div>
                      @endforeach
                    </div>
                  </div>
                </div>
                @else
                  <p>@lang('label.no_scheduled_articles')</p>
                @endif
                <div class="d-flex justify-content-center">
                  {{ $query->links() }}
                </div>
              </div>
            </div>
          </div>
        </div>
  </div>
</div>
@endsection

{{--
  <div class="py-2 col-lg-12">
    <div class="row">
      <div class="col-12">
        <div class="row">
          <div class="ml-lg-4">
            Nome Articolo
          </div>
          <div class="ml-auto">
            Commenti
          </div>
          <div class="ml-auto mr-5">
            Visualizzazioni
          </div>
        </div>
      </div>
      <div class="mt-4">
        @foreach($query as $value)
        <div class="col-12">
          <div class="row">
            <div class="col-3">
              <a data-script="view" data-view="{{ $value->id }}" href="#view-{{ $value->id }}">
                <div class="row">
                  <div class="col-lg-8">
                    <img class="card-img-top" src="{{ $value->getBackground() }}" alt="Copertina">
                  </div>
                  <div class="col-lg-4">
                    <strong>{{ $value->titolo }}</strong>
                  </div>
                </div>
              </a>
            </div>
            <div class="ml-auto">
              0
            </div>
            <div class="ml-auto">
              0
            </div>
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </div>
--}}
