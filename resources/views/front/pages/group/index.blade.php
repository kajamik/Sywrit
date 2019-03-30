@extends('front.layout.app')

@section('title', $query->nome.' -')

@section('main')
@include('front.components.group.top_bar')
<div class="publisher-content">
  <div class="py-3">
    @if(count($query->articoli))
    <div class="col-lg-12">
      <div class="row" id="articles">
        @foreach($query->articoli->take(12) as $n => $articolo)
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
      <p>Questa redazione non ha ancora pubblicato alcun articolo</p>
    @endif
    </div>
  </div>
</div>
<script>
App.follow('#follow',{url:'{{ url("follow") }}',data:{'id':{{ $query->id }}}},false);
App.insl('articles');
</script>
</div>
@endsection
