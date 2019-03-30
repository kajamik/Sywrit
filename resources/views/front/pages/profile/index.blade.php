@extends('front.layout.app')

@section('title', $query->nome.' '.$query->cognome.' -')

@section('main')
@include('front.components.profile.top_bar')
        <div class="publisher-content">
          <div class="py-3">
            @if($count)
            <div class="col-lg-12">
              <div class="row" id="articles">
                @foreach($articoli->take(12) as $n => $articolo)
                <div class="col-lg-4 col-sm-8 col-xs-12">
                  <a href="{{ url('read/'. $articolo->slug) }}">
                    <div class="card-header">{{ $articolo->created_at->diffForHumans() }}</div>
                    <div class="card border-0">
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
            <script>
              App.insl("articles");
            </script>
            @else
              <p>Questo utente non ha ancora iniziato a pubblicare</p>
            @endif
          </div>
        </div>
    </div>
  </div>
</div>
</div>
@endsection
