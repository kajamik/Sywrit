@extends('front.layout.app')

@section('title', 'Articoli Salvati -')

@section('main')
<div class="container">
  <div class="publisher-home">
    <section class="publisher-header" style="background-image: url({{asset(\Auth::user()->getBackground())}})">
      <div class="container">
        <img class="publisher-logo" src="{{asset(\Auth::user()->getAvatar())}}" alt="Logo">
        <div class="info">
          <span>{{\Auth::user()->nome}} {{\Auth::user()->cognome}}</span>
        </div>
      </div>
    </section>
    <section class="publisher-body">
      <div class="container">
        <ul>
          <li><a href="{{url(\Auth::user()->slug)}}">Home</a></li>
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
                      @if($articolo->copertina)
                        <img class="card-img-top" src="{{asset('storage/articles/'.$articolo->copertina)}}" alt="Copertina">
                      @endif
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
</section>
  <script>
    App.insl('articles');
  </script>
</div>
@endsection
