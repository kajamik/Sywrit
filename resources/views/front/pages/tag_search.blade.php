@extends('front.layout.app')

@section('main')
  <div class="publisher-home">
    <div class="publisher-body">
      <h3>{{ __('label.search.found_tag_by', ['count' => $query->count(), 'name' => $slug]) }}</h3>
      <div class="col-lg-12">
        @if($query->count())
        <div class="row">
          @foreach($query as $value)
          <div class="col-lg-3 col-sm-12 col-xs-12">
          <a href="{{ url('read/'.$value->article_slug)}}">
            <div class="card-header">{{ \Carbon\Carbon::parse($value->created_at)->diffForHumans() }}</div>
            <div class="card">
              <img class="card-img-top" src="{{ $value->getBackground() }}" alt="{{ $value->article_title }}">
                <div class="card-body">
                  @if($value->topic_id)
                    <span>{{ $value->topic_name }}</span>
                  @endif
                  <h5 class="card-title" title="{{ $value->article_title }}">{{ str_limit($value->article_title, 33) }}</h5>
                  <p>
                    @if($value->bot_message == '1')
                      {{ __('label.article.bot_message') }}
                    @else
                      {{ __('label.article.published_by') }}
                      @if($value->id_editore)
                      <a href="{{ url($value->publisher_slug) }}">
                        <span>{{ $value->publisher_name }}</span>
                      </a>
                      @else
                      <a href="{{ url($value->user_slug) }}">
                        <span>{{ $value->user_name }} {{ $value->user_surname }}</span>
                      </a>
                      @endif
                    @endif
                </div>
            </div>
          </a>
        </div>
          @endforeach
        </div>
        {{ $query->links() }}
        @endif
      </div>
    </div>
  </div>
@endsection
