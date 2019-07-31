@php
  $feeds = \App\Models\Articoli::take(2)->inRandomOrder()
            ->where('id', '!=', $query->id)
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
  <div class="col-lg-12 col-md-12 col-sm-12">
    <div class="row">
      @foreach($feeds as $value)
      <div class="col-lg-3 col-sm-12 col-xs-12">
      <a href="{{ url('read/'.$value->slug)}}">
        <div class="card-header">{{ $value->created_at->diffForHumans() }}</div>
        <div class="card border-0">
          <img class="card-img-top" src="{{ $value->getBackground() }}" alt="">

            <h4 class="card-title">{{ $value->titolo }}</h4>
            <div class="author">
              Pubblicato da
              @if(!empty($value->id_gruppo))
              <a href="{{ url($value->getRedazione->slug) }}"><span><span>{{ $value->getRedazione->name }}</span></a>
              @else
              <a href="{{ url($value->getAutore->slug) }}"><span><span>{{ $value->getAutore->name }} {{ $value->getAutore->surname }}</span></a>
              @endif
            </div>
        </div>
      </a>
    </div>
    @endforeach
    </div>
  </div>
</div>
@endif
