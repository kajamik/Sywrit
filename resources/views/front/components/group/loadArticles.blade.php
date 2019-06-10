@foreach($articoli as $value)
<div class="col-lg-3 col-sm-6 col-xs-12">
<a href="{{ url('read/'.$value->article_slug)}}">
  <div class="card-header">{{ $value->created_at->diffForHumans() }}</div>
  <div class="card">
    <img class="card-img-top" src="{{ asset($value->getBackground()) }}" alt="Copertina Articolo">
    <div class="card-body">
      @if($value->topic_id)
        <span>{{ $value->topic_name }}</span>
      @endif
      </a>
      <h5 class="card-title">{{ $value->article_title }}</h5>
      <p>{!! str_limit(preg_replace('/(<.*?>)|(&.*?;)/', '', $value->article_text), 100) !!}</p>
      <p>
        Scritto da
          <a href="{{ url($value->user_slug) }}">
            <span>{{ $value->user_name }} {{ $value->user_surname }}</span>
          </a>
      </p>
    </div>
  </div>
</a>
</div>
@endforeach
