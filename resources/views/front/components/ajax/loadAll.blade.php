@foreach($articoli as $value)
<div class="col-lg-4 col-sm-12 col-xs-12">
<a href="{{ url('read/'.$value->article_slug) }}">
  <div class="card-header">
    {{ \Carbon\Carbon::parse($value->created_at)->diffForHumans() }}
  </div>
  <div class="card">
    <img class="card-img-top" src="{{ $value->getBackground() }}" alt="{{ $value->article_title }}" />
      <div class="card-body">
        @if($value->topic_id)
          <span>{{ $value->topic_name }}</span>
        @endif
        <h5 class="card-title" title="{{ $value->article_title }}">{{ str_limit($value->article_title, 33) }}</h5>
        <p>{!! str_limit(preg_replace('/(<.*?>)|(&.*?;)/', '', $value->article_text), 100) !!}</p>
        @if($value->bot_message == '1')
        <p>Messaggio generato dal sistema</p>
        @else
        <div class="author">
          Pubblicato da
          @if($value->id_editore)
          <a href="{{ url($value->publisher_slug) }}">
            <span>{{ $value->publisher_name }}</span>
          </a>
          @else
          <a href="{{ url($value->user_slug) }}">
            <span>{{ $value->user_name }} {{ $value->user_surname }}</span>
          </a>
          @endif
        </div>
        @endif
      </div>
  </div>
</a>
</div>
@endforeach
