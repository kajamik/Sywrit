@foreach($articoli as $value)
<div class="col-lg-3 col-sm-6 col-xs-12">
<a href="{{ url('read/'.$value->article_slug)}}">
  <div class="card-header">{{ $value->created_at->diffForHumans() }}</div>
  <div class="card border-0">
    <img class="card-img-top" src="{{asset($value->getBackground())}}" alt="Copertina Articolo">
    @if($value->topic_id)
    <span>{{ $value->topic_name }}</span>
    @endif
      <h4 class="card-title">{{ $value->article_title }}</h4>
  </div>
</a>
</div>
@endforeach
