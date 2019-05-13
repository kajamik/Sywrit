@extends('front.layout.app')

@section('group::bio')
  @if(!empty($query->biography))
  <h2>Descrizione</h2>
  <div class="col-lg-12">
    <p>{!! $query->biography !!}</p>
  </div>
  <hr/>
  @endif
@endsection

@section('main')
@include('front.components.group.top_bar')
<div class="publisher-content">
  <div class="py-3">
    @if($articoli->count())
    <div class="col-lg-12">
      <div class="row" id="articles">
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
              <h6>{{ str_limit(strip_tags($value->article_text), 100) }}</h6>
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
      </div>
    </div>
    <script>
      App.insl("articles");
    </script>
    @else
      <p>Questa redazione non ha ancora pubblicato alcun articolo</p>
    @endif
    </div>
  </div>
</div>
</div>
@endsection
