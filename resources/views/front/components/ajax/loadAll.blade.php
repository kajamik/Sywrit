@if(!empty($articoli))
@foreach($articoli as $value)
  <div class="col-lg-3 col-sm-8 col-xs-12">
    <a href="{{ url('read/'.$value->slug)}}">
      <div class="card">
        <img class="card-img-top" src="{{asset($value->getBackground())}}" alt="Copertina Articolo">
        <div class="card-body card-block">
          <h5 class="card-title">{{ $value->titolo }}</h5>
          <div class="author">
            Pubblicato da
            @if(!empty($value->id_gruppo))
              <a href="{{ url($value->getRedazione->slug) }}"><span><span>{{ $value->getRedazione->nome }}</span></a>
            @else
              <a href="{{ url($value->getAutore->slug) }}"><span><span>{{ $value->getAutore->nome }}</span></a>
            @endif
          </div>
        </div>
      </div>
    </a>
  </div>
@endforeach
@endif
