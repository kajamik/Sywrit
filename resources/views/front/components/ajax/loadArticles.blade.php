@foreach($articoli->take(12) as $articolo)
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
