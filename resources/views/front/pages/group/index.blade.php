@extends('front.layout.app')

@section('title', $query->nome.' -')

@section('main')
<div class="container">
  <div class="publisher-home">
    <section class="publisher-header" style="background-image: url({{asset($query->getBackground())}})">
      <div class="container">
        <img class="publisher-logo" src="{{asset($query->getLogo())}}" alt="Logo">
        <div class="info">
          <span>{{$query->nome}} {{$query->cognome}}</span>
        </div>
      </div>
    </section>
      <section class="publisher-body">
        @include('front.components.group.top_bar')
        <div class="container my-5">
        @if(!empty($query->articoli)) {{-- Se sono presenti articoli --}}
        <div class="col-lg-12">
          <div class="row" id="articles">
            @foreach($query->articoli as $articolo)
            <div class="col-lg-3 col-sm-8 col-xs-12">
              <a href="{{ url('read/'. $articolo->slug) }}">
                <div class="card">
                  <img class="card-img-top" src="{{asset('storage/publishers/articoli/'.$articolo->copertina)}}" alt="Copertina">
                  <div class="card-body">
                    <h4 class="card-title">{{ $articolo->titolo }}</h4>
                  </div>
                </div>
              </a>
            </div>
            @endforeach
          </div>
        </div>
        @endif
        </div>
      </section>
  <script>
    App.follow('button#follow',{url:'{{url("follow")}}',data:{'id':{{ $query->id }},'mode':'g'}},false);
    //App.loadData('#articles','?page=');
  </script>
  </div>
</div>
@endsection
