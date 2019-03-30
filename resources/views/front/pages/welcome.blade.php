@extends('front.layout.app')

@section('main')
<style>
h2, h5 {
  padding: 8px;
  color: #000;
  border-radius: 4px;
  text-transform: uppercase;
  font-size: 23px;
}
</style>

<div id="_home" class="container">
  <div class="publisher-home">
    <div class="publisher-body">

    @guest
    <div class="block-hero">
      <div class="container">
        <div class="caption">
          <h1>Benvenuto su Sywrit</h1>
          <h3>La piattaforma italiana di scrittura</h3>
          <a href="{{ url('register') }}">
            <button style="padding:7px;margin:6px;" class="btn btn-dark">
              Crea il tuo primo articolo
            </button>
            <button style="padding:7px;margin:6px;" class="btn btn-dark">
              Il nostro progetto
            </button>
          </a>
        </div>
      </div>
    </div>
    @endguest

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
                    <a href="{{ url($value->user_slug) }}"><span><span>{{ $value->user_name }} {{ $value->user_surname }}</span></a>
                </div>
            </div>
          </a>
        </div>
      @endforeach
      </div>
      <hr/>
      @endif

        {{--
        <div class="col-lg-3 col-sm-6 col-xs-12">
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
      <hr/>
      --}}

      @if(count($articoli))
      <div class="py-2 row" id="news">
      @foreach($articoli as $value)
        <div class="col-lg-3 col-sm-4 col-xs-12">
          <a href="{{ url('read/'.$value->article_slug)}}">
            <div class="card-header">{{ $value->created_at->diffForHumans() }}</div>
            <div class="card border-0">
              <img class="card-img-top" src="{{asset($value->getBackground())}}" alt="Copertina Articolo">
                <h4 class="card-title">{{ $value->article_title }}</h4>
                <div class="author">
                  Pubblicato da
                    <a href="{{ url($value->user_slug) }}"><span><span>{{ $value->user_name }} {{ $value->user_surname }}</span></a>
                </div>
            </div>
          </a>
        </div>
      @endforeach
      </div>
      @endif

      <script>
        App.insl('news');
      </script>

    </div>
</div>

</div>
@endsection
