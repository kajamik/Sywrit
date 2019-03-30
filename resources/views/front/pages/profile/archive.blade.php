@extends('front.layout.app')

@section('title', 'Articoli Salvati -')

@section('main')
<style>
#nav > li {
  display: inline-block;
  margin-top: 5px;
}
#nav > li:not(:last-child)::after {
  content: '\00a0|';
}
._ou {
  cursor: pointer;
}
#customMsg {
  min-height: 200px;
}
</style>
<div class="container">
  <div class="publisher-home">
    <div class="publisher-header" style="background-image: url({{asset(Auth::user()->getBackground())}})">
      <div class="container">
        <img class="publisher-logo" src="{{asset(Auth::user()->getAvatar())}}" alt="Logo">
        <div class="info">
          <span>{{Auth::user()->nome}} {{Auth::user()->cognome}}</span>
        </div>
      </div>
    </div>
    <div class="publisher-body">
      <div class="container">
        <ul id='nav'>
          <li><a href="{{ url(Auth::user()->slug) }}">Home</a></li>
          <li><a href="{{ url(Auth::user()->slug.'/about') }}">Informazioni</a></li>
          <li><a href="{{ url(Auth::user()->slug.'/archive') }}">Articoli Salvati</a></li>
      </ul>
      <hr/>
        <div class="publisher-content">
          <h1>Archivio</h1>
          <div class="py-3">
            @if($query->count())
            <div class="col-lg-12">
              <div class="row" id="articles">
                @foreach($query->take(12) as $articolo)
                <div class="col-lg-4 col-sm-8 col-xs-12">
                  <a href="{{ url('read/'. $articolo->slug) }}">
                    <div class="card">
                      <img class="card-img-top" src="{{asset($articolo->getBackground())}}" alt="Copertina">
                      <div class="card-body">
                        <h4 class="card-title">{{ $articolo->titolo }}</h4>
                      </div>
                    </div>
                  </a>
                </div>
                @endforeach
              </div>
            </div>
            @else
              <p>Non hai nessun articolo salvato</p>
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
