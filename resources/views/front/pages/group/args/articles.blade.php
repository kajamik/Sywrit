@include('front.components.group.top_bar')

@if(!empty($query->articoli)) {{-- Se sono presenti articoli --}}
<div class="col-lg-12">
  <div class="row">
    @foreach($query->articoli->take(12) as $articolo)
      <div class="col-lg-3 col-sm-8 col-xs-12">
        <a href="{{ url('read/'. $articolo->slug) }}">
          <div class="card">
            <img class="card-img-top" src="{{asset($articolo->getBackground())}}" alt="Card image cap">
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
