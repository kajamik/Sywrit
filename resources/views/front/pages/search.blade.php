@extends('front.layout.app')

@section('main')
  <div class="publisher-home">
    <div class="publisher-body">
      <h3>{{ __('label.search.found_items_by', ['count' => $query->count(), 'name' => $slug]) }}</h3>
      <div class="col-lg-12">
        @if($query->count())
        <div class="row" id="queries">
          @foreach($query as $value)
            @isset($value->article_title)
            <div class="col-lg-3 col-sm-12 col-xs-12">
            <a href="{{ url('read/'.$value->article_slug)}}">
              <div class="card-header">{{ \Carbon\Carbon::parse($value->created_at)->diffForHumans() }}</div>
              <div class="card">
                <img class="card-img-top" src="{{ $value->getBackground() }}" alt="{{ $value->article_title }}" />
                  <div class="card-body">
                    @if($value->topic_id)
                      <span>{{ $value->topic_name }}</span>
                    @endif
                    <h5 class="card-title" title="{{ $value->article_title }}">{{ str_limit($value->article_title, 33) }}</h5>
                    {{--
                      <p>{!! str_limit(preg_replace('/(<.*?>)|(&.*?;)/', '', $value->article_text), 100) !!}</p>
                    --}}
                    @if($value->bot_message == '1')
                    <p>Messaggio generato dal sistema</p>
                    @else
                    <div class="author">
                      @if($value->id_editore)
                        @lang('label.article.published_by', ['name' => $value->publisher_name, 'url' => url($value->publisher_slug)])
                      @else
                        @lang('label.article.published_by', ['name' => $value->user_name.' '.$value->user_surname, 'url' => url($value->user_slug)])
                      @endif
                    </div>
                    @endif
                  </div>
              </div>
            </a>
          </div>
            @else
            <div class="col-lg-3 col-sm-4 col-xs-12">
              <a href="{{ url($value->slug) }}">
                <div class="card">
                  <img class="card-img-top" src="{{ $value->getAvatar() }}" alt="{{ $value->name }} {{ $value->surname}}">
                  <div class="card-body">
                    @if(!empty($value->direttore))
                    <span>Redazione</span>
                    @else
                    <span>{{ __('label.search.user') }}</span>
                    @endif
                    <a href="{{ url($value->slug) }}">
                      <h4>{{ $value->name }} {{ $value->surname }}</h4>
                    </a>
                  </div>
                </div>
              </a>
            </div>
            @endisset
          @endforeach
        </div>
        @endif
      </div>
    </div>
  </div>
@endsection
