@extends('front.layout.app')

@section('title', $query->nome.' -')

@section('main')
  <div class="publisher-home">
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
@endsection
