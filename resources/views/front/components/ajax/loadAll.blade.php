@if(!empty($articoli))
@foreach($articoli as $value)
  <div class="col-lg-3 col-sm-4 col-xs-12">
    <a href="{{ url('read/'.$value->article_slug)}}">
      <div class="card-header">{{ $value->created_at->diffForHumans() }}</div>
      <div class="card border-0">
        <img class="card-img-top" src="{{asset($value->getBackground())}}" alt="Copertina Articolo">
          <h4 class="card-title">{{ $value->article_title }}</h4>
          <div class="author">
            Pubblicato da
              <a href="{{ url($value->user_slug) }}"><span><span>{{ $value->user_name }} {{ $value->user_surname }}</span></a>
          </div>
      </div>
    </a>
  </div>
@endforeach
@endif
