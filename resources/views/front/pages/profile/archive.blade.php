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
  content: '\00a0|';
}
._ou {
  cursor: pointer;
}
#customMsg {
  min-height: 200px;
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
      <ul id='nav'>
        <li><a href="{{ url(Auth::user()->slug) }}">Profilo</a></li>
        <li><a href="{{ url(Auth::user()->slug.'/about') }}">Contatti</a></li>
        <li><a href="{{ url(Auth::user()->slug.'/archive') }}">Articoli Salvati</a></li>
      </ul>
    </nav>
    <div class="publisher-body">
      <hr/>
        <div class="publisher-content">
          <h1>Archivio</h1>
          <div class="py-3">
            @if($query->count())
            <div class="col-lg-12">
              <div class="row" id="articles">
                @foreach($query->take(12) as $value)
                <div class="col-lg-4 col-sm-8 col-xs-12">
                  <a href="{{ url('read/archive/'. $value->slug) }}">
                    <div class="card">
                      <img class="card-img-top" src="{{ $value->getBackground() }}" alt="Copertina">
                      <div class="card-body">
                        <h5 class="card-title">{{ $value->titolo }}</h5>
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
<script>
  App.insl('articles');
</script>
@endsection
