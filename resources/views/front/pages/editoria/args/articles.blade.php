@if(!empty($query->articoli)) {{-- Se sono presenti articoli --}}
<div class="col-lg-12">
  <div class="row">
    @foreach($query->articoli->take(12) as $articolo)
      <div class="col-lg-2 col-sm-6 col-xs-12">
        <a href="{{ url('read/'.$articolo->id.'-'.$articolo->slug) }}">
          <div class="card">
            <img class="card-img-top" src="{{asset($articolo->getBackground())}}" alt="Card image cap">
            <div class="card-body">
              <h5 class="card-title">{{ $articolo->titolo }}</h5>
              <p class="card-text">{!! str_limit(strip_tags($articolo->testo), 75) !!}</p>
            </div>
          </div>
        </a>
      </div>
    @endforeach
  </div>
</div>
@endif
