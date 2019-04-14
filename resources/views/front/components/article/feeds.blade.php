@php
  $feeds = \App\Models\Articoli::take(2)->inRandomOrder()
            ->where('id', '!=', $query->id)
            ->where(function ($sql) use ($tags){
                foreach($tags as $value){
                  $sql->where('tags', 'like', '%'.$value.'%');
                }
            })->get();
@endphp
@if($feeds->count())
<div class="feeds">
<h3>Articoli simili</h3>
<div class="row">
@foreach($feeds as $value)
<div class="row col-8">
  <div class="col-lg-3 col-md-4 col-xs-4">
    <img src="{{ asset($value->getBackground()) }}" alt="" />
  </div>
  <div class="my-4 col-sm-8">
    <a href="{{ $value->slug }}">
      <h4 class="text-uppercase">{{ $value->titolo }}</h4>
      <p>Pubblicato da
        @if(!empty($value->id_gruppo))
        <a href="{{ url($value->getRedazione->slug) }}"><span><span>{{ $value->getRedazione->nome }}</span></a>
        @else
        <a href="{{ url($value->getAutore->slug) }}"><span><span>{{ $value->getAutore->nome }} {{ $value->getAutore->cognome }}</span></a>
        @endif
      </p>
    </a>
    </div>
</div>
@endforeach
</div>

</div>
@endif
