@extends('front.layout.app')

@section('title', $query->nome.' -')

@section('main')
<div class="container">
  <div class="publisher-home">
    <section class="publisher-header" style="background-image: url({{asset($query->getBackground())}})">
      <div class="container">
        <img class="publisher-logo" src="{{asset($query->getLogo())}}" alt="Logo">
        <div class="info">
          <span>{{$query->nome}}</span>
        </div>
      </div>
    </section>
      <section class="publisher-body">
        @if(\Session::get('type') == 'container_right__small')
        <div class="alert-toggle alert alert-danger">
          <h2>{{\Session::get('message')}}</h2>
        </div>
        @endif
        @include('front.components.group.top_bar')
        <div class="container my-5">
        @if(count($query->articoli)) {{-- Se sono presenti articoli --}}
        <div class="col-lg-12">
          <div class="row" id="articles">
            @foreach($query->articoli->take(12) as $n => $articolo)
            <div class="@if($n > 0) col-lg-4 @else col-lg-8 @endif col-sm-8 col-xs-12">
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
          <p>Questa redazione non ha ancora pubblicato alcun articolo</p>
        @endif
        </div>
      </section>
  <script>
    App.follow('#follow',
      {
        url:'{{url("follow")}}',
        data:{'id':{{ $query->id }},'mode':'g'}
      },
        false
      );
    App.insl('articles');
  </script>
  </div>
</div>
@endsection
