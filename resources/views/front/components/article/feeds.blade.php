@php
  $feeds = \App\Models\Articoli::take(2)->inRandomOrder()
            ->where('id', '!=', $query->id)
            ->where('status', '1')
            ->where(function ($sql) use ($tags){
                foreach($tags as $value){
                  $sql->where('tags', 'like', '%'. $value .'%');
                }
            })
            ->where(function($sql) use ($query){
              $sql->where('titolo', 'like', '%'. $query->titolo .'%')
                    ->orWhere('testo', 'like', '%'. $query->testo .'%')
                    ->orWhere('id_autore', $query->id_autore);
            })
            ->get();

@endphp
@if($feeds->count())
<hr/>
<div class="feeds">
  <h3>Articoli simili</h3>
  @foreach($feeds as $value)
  <div class="py-2">
    <div class="row">
      <div class="col-lg-2 col-md-4 col-sm-4">
        <img class="card-img-top" src="{{ asset($value->getBackground()) }}" alt="Copertina Articolo" />
      </div>
      <div class="my-2 col-md-8 col-sm-8">
        <a href="{{ $value->slug }}">
          <h4 class="text-uppercase">{{ $value->titolo }}</h4>
          <p>Pubblicato da
            @if(!empty($value->id_gruppo))
            <a href="{{ url($value->getRedazione->slug) }}"><span><span>{{ $value->getRedazione->name }}</span></a>
            @else
            <a href="{{ url($value->getAutore->slug) }}"><span><span>{{ $value->getAutore->name }} {{ $value->getAutore->surname }}</span></a>
            @endif
          </p>
        </a>
      </div>
    </div>
  </div>
  @endforeach
</div>
@endif
