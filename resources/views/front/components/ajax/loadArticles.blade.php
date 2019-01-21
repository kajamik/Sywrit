@foreach($articoli->take(12) as $articolo)
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
