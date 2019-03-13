@extends('front.layout.app')

@section('title', $query->nome.' '.$query->cognome.' -')

@section('main')
@include('front.components.profile.top_bar')
        {{--@if(!empty($query->biography))
        <div class="py-4">
          <h4>Biografia</h4>
          <p>{!! $query->biography !!}</p>
        </div>
        <hr/>
        @endif--}}
        <div class="publisher-content">
          <div class="py-3">
            @if($count)
            <div class="col-lg-12">
              <div class="row" id="articles">
                @foreach($articoli->take(12) as $n => $articolo)
                <div class="@if($n > 0) col-lg-4 @else col-lg-12 @endif col-sm-8 col-xs-12">
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
</section>
</div>
@endsection
