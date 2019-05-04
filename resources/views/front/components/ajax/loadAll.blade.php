@foreach($articoli as $value)
  <div class="col-lg-3 col-sm-4 col-xs-12">
    <a href="{{ url('read/'.$value->article_slug)}}">
      <div class="card-header">{{ $value->created_at->diffForHumans() }}</div>
      <div class="card border-0">
        <img class="card-img-top" src="{{asset($value->getBackground())}}" alt="Copertina Articolo">
        @if($value->topic_id)
        <span>{{ $value->topic_name }}</span>
        @endif
          <h4 class="card-title">{{ $value->article_title }}</h4>
          <div class="author">
            Pubblicato da
            @if($value->id_editore)
            <a href="{{ url($value->publisher_slug) }}"><span>{{ $value->publisher_name }}</span></a>
            @else
            <a href="{{ url($value->user_slug) }}"><span>{{ $value->user_name }} {{ $value->user_surname }}</span></a>
            @endif
          </div>
      </div>
    </a>
  </div>
@endforeach
