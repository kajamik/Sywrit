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

<div class="block-hero">
  <div class="container">
    <div class="caption">
      <h1>Benvenuto su Sywrit</h1>
      <h3>La piattaforma di scrittura</h3>
      <a href="{{ url('register') }}">
        <button style="padding:15px" class="btn btn-dark">
          Crea il tuo primo articolo
        </button>
      </a>
    </div>
  </div>
</div>

<div id="_home" class="container">
  <div class="publisher-home">
    <div class="publisher-body">

      @if(count($ultimi_articoli))
      <h2>Ultimi articoli</h2>
      <div class="row">
      @foreach($ultimi_articoli as $value)
        <div class="col-lg-4 col-sm-6 col-xs-12">
          <a href="{{ url('read/'.$value->slug)}}">
            <div class="card">
              <img class="card-img-top" src="{{asset($value->getBackground())}}" alt="Copertina Articolo">
              <div class="card-body">
                <h5 class="card-title">{{ $value->titolo }}</h5>
                <div class="author">
                  Pubblicato da
                  @if(!empty($value->id_gruppo))
                    <a href="{{ url($value->getRedazione->slug) }}"><span><span>{{ $value->getRedazione->nome }}</span></a>
                  @else
                    <a href="{{ url($value->getAutore->slug) }}"><span><span>{{ $value->getAutore->nome }} {{ $value->getAutore->cognome }}</span></a>
                  @endif
                </div>
              </div>
            </div>
          </a>
        </div>
      @endforeach
      </div>
      @endif

      {{--
        <hr />
        <div class="col-lg-5 col-sm-6 col-xs-12">
        <div class="card text-center">
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

      @if(count($articoli))
      <hr/>
      <h2>Articoli</h2>
      <div class="row" id="news">
      @foreach($articoli as $value)
        <div class="col-lg-3 col-sm-8 col-xs-12">
          <a href="{{ url('read/'.$value->slug)}}">
            <div class="card">
              <img class="card-img-top" src="{{asset($value->getBackground())}}" alt="Copertina Articolo">
              <div class="card-body card-block">
                <h5 class="card-title">{{ $value->titolo }}</h5>
                <div class="author">
                  Pubblicato da
                  @if(!empty($value->id_gruppo))
                    <a href="{{ url($value->getRedazione->slug) }}"><span><span>{{ $value->getRedazione->nome }}</span></a>
                  @else
                    <a href="{{ url($value->getAutore->slug) }}"><span><span>{{ $value->getAutore->nome }} {{ $value->getAutore->cognome }}</span></a>
                  @endif
                </div>
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
