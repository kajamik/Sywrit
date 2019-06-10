@foreach($articoli as $value)
<div class="col-lg-3 col-sm-6 col-xs-12">
  <a href="{{ url('read/'.$value->article_slug) }}">
    <div class="card-header">{{ \Carbon\Carbon::parse($value->published_at)->diffForHumans() }}</div>
    <div class="card border">
      <img class="card-img-top" src="{{asset($value->getBackground())}}" alt="Copertina Articolo">
      <div class="card-body">
        @if($value->topic_id)
          <span>{{ $value->topic_name }}</span>
        @endif
        <h5 class="card-title">{{ $value->article_title }}</h5>
        <p>{!! str_limit(preg_replace('/(<.*?>)|(&.*?;)/', '', $value->article_text), 100) !!}</p>
      </div>
    </div>
  </a>
</div>
@endforeach
