@extends('front.layout.app')

@section('main')

<div id="_home" class="container">

  <div class="publisher-home">

      <div class="block-hero"></div>

    <div class="publisher-body">


    {{--<div class="row">
      <div class="col-lg-4 col-sm-4 col-xs-12">
      <div class="card border-0 text-center">
        <div class="card-header">
          <span class="far fa-star"></span>
          Sponsorizzazione
        </div>
        <a href="#">
          <div class="card-body" style="padding:0">
            <img style="width:100%;" src="{{ asset('upload/google-ads.png') }}" alt="" />
          </div>
          <div class="card-footer text-muted">
            <button class="btn btn-link">Ottieni maggiori informazioni</button>
          </div>
        </a>
      </div>
    </div>
    </div>--}}

      @if(count($ultimi_articoli))
      <div class="row">
      @foreach($ultimi_articoli as $value)
        <div class="col-lg-4 col-sm-12 col-xs-12">
          <a href="{{ url('read/'.$value->article_slug)}}">
            <div class="card-header">{{ $value->created_at->diffForHumans() }}</div>
            <div class="card border-0">
              <img class="card-img-top" src="{{ asset($value->getBackground()) }}" alt="Copertina Articolo">
                <h4 class="card-title">{{ $value->article_title }}</h4>
                <div class="author">
                  Pubblicato da
                    <a href="{{ url($value->user_slug) }}"><span>{{ $value->user_name }} {{ $value->user_surname }}</span></a>
                </div>
            </div>
          </a>
        </div>
      @endforeach
      </div>
      <hr/>
      @endif

      <div class="col-md-12">
      <div class="row" id="news">

        <div class="col-lg-8 col-md-12 col-sm-12">
          @if(count($articoli))
          <div class="row">
            @foreach($articoli as $value)
            <div class="col-lg-4 col-sm-6 col-xs-12">
            <a href="{{ url('read/'.$value->article_slug)}}">
              <div class="card-header">{{ $value->created_at->diffForHumans() }}</div>
              <div class="card border-0">
                <img class="card-img-top" src="{{asset($value->getBackground())}}" alt="Copertina Articolo">
                  <h4 class="card-title">{{ $value->article_title }}</h4>
                  <div class="author">
                    Pubblicato da
                      <a href="{{ url($value->user_slug) }}"><span>{{ $value->user_name }} {{ $value->user_surname }}</span></a>
                  </div>
              </div>
            </a>
          </div>
          @endforeach
        </div>
        @endif
        </div>

        <div class="col-lg-4 col-xs-12">
          {{--<div class="col-lg-12 col-sm-12 col-xs-12">
          <div class="card border-0 text-center">
            <div class="card-header">
              <span class="far fa-star"></span>
              Sponsorizzazione
            </div>
            <a href="#">
              <div class="card-body" style="padding:0">
                <img style="width:100%;" src="{{ asset('upload/google-ads.png') }}" alt="" />
              </div>
              <div class="card-footer text-muted">
                <button class="btn btn-link">Ottieni maggiori informazioni</button>
              </div>
            </a>
          </div>
        </div>--}}
        <div class="border-left col-lg-12 col-sm-12 col-xs-12">
          <h2>Articoli del mese</h2>
          <hr/>
          <ul class="list-group mb-3">
            @foreach($top as $n => $value)
            <div class="border-bottom pb-3">
                <div class="d-flex w-100">
                  <medium>{{ $n+1 }}</medium>
                  <a href="{{ url('read/'.$value->article_slug) }}">
                    <h5 class="mb-1">{{ $value->article_title}}</h5>
                  </a>
                  <small>{{ $value->created_at->diffForHumans() }}</small>
                </div>
                Di
                <a href="{{ url($value->user_slug) }}">
                  <medium>{{ $value->user_name }} {{ $value->user_surname }}</medium>
                </a>
              </div>
              @endforeach

          </div>
        </div>
      </div>

    </div>

      <script>
        App.insl('news');
      </script>

    </div>
</div>

</div>
@endsection
