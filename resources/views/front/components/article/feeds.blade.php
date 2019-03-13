@php
  $query = \App\Models\Articoli::take(2)->inRandomOrder()->get();
@endphp
@if($query->count())
<div class="feeds">
<h3>Articoli simili</h3>
<div class="row">
@foreach($query as $value)
<div class="col-lg-4 col-sm-6 col-xs-12">
  <a href="{{ url('read/'.$value->slug)}}">
    <div class="card">
      <img class="card-img-top" src="{{asset($value->getBackground())}}" alt="Copertina Articolo">
      <div class="card-body">
        <h5 class="card-title">{{ $value->titolo }}</h5>
        <div class="author">
          Pubblicato da
          @if(!empty($value->id_gruppo))
            <a href="{{ url($value->getRedazione->slug) }}"><span><span>{{ $value->getRedazione->nome }}</span></a>
          @else
            <a href="{{ url($value->getAutore->slug) }}"><span><span>{{ $value->getAutore->nome }} {{ $value->getAutore->cognome }}</span></a>
          @endif
        </div>
      </div>
    </div>
  </a>
</div>
@endforeach
</div>
</div>
@endif
