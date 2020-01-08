@php
  $feeds = \App\Models\Articoli::take(2)->inRandomOrder()
            ->where('id', '!=', $query->id)
            /*->where(function ($sql) use ($tags){
                foreach($tags as $value){
                  $sql->where('tags', 'like', '%'. $value .'%');
                }
            })
            ->where(function($sql) use ($query){
              $sql->where('titolo', 'like', '%'. $query->titolo .'%')
                    ->orWhere('id_autore', $query->id_autore);
            })*/
            ->get();
@endphp
@if($feeds->count())
<hr/>
<div class="feeds">
  <h3>@lang('label.article.interest')</h3>
  <div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
      <div class="row">
        @foreach($feeds as $value)
        <div class="col-lg-3 col-sm-3 col-xs-12">
        <a href="{{ url('read/'.$value->slug)}}">
          <div class="card-header">{{ $value->created_at->diffForHumans() }}</div>
          <div class="card border-0">
            <img class="card-img-top" src="{{ $value->getBackground() }}" alt="">

              <h4 class="card-title">{{ $value->titolo }}</h4>
              <div class="author">
                @if($value->id_editore)
                  @lang('label.article.published_by', ['name' => $value->getRedazione->name, 'url' => url($value->getRedazione->slug)])
                @else
                  @lang('label.article.published_by', ['name' => $value->getAutore->name.' '.$value->getAutore->surname, 'url' => url($value->getAutore->slug)])
                @endif
              </div>
          </div>
        </a>
      </div>
      @endforeach
      </div>
    </div>
  </div>
</div>
@endif
