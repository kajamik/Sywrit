@extends('front.layout.app')

@section('title', $topic->name. ' -')

@section('main')
<div class="container">
  <div class="publisher-home">
    <div class="publisher-body">

      <h1>{{ $topic->name }}</h1>

      <div class="row">

        <div class="col-lg-12 col-md-12 col-sm-12">
          @if(count($articoli))
          <div class="row" id="news">
            @foreach($articoli as $value)
            <div class="col-lg-3 col-sm-6 col-xs-12">
            <a href="{{ url('read/'.$value->article_slug)}}">
              <div class="card-header">{{ $value->created_at->diffForHumans() }}</div>
              <div class="card border-0">
                <img class="card-img-top" src="{{asset($value->getBackground())}}" alt="Copertina Articolo">
                  <h4 class="card-title">{{ $value->article_title }}</h4>
                  <div class="author">
                    Pubblicato da
                      <a href="{{ url($value->user_slug) }}"><span>{{ $value->user_name }} {{ $value->user_surname }}</span></a>
                  </div>
              </div>
            </a>
          </div>
          @endforeach
        </div>
        @else
        <div class="col-lg-4 col-sm-8 col-xs-12">
        <a href="{{ url('write?_topic='.$topic->slug)}}">
          <div class="card">
            <div class="card-header">Crea un nuovo articolo</div>
            <img class="card-img-top" src="{{ asset('upload/topics/'.$topic->slug.'.jpg') }}" alt="Crea nuovo articolo">
          </div>
        </a>
      </div>
        @endif
        </div>

      </div>

    </div>
  </div>
</div>

<script>
  App.insl('news');
</script>

@endsection
