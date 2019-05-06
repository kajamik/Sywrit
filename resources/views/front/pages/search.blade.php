@extends('front.layout.app')

@section('title', $slug.' - ')

@section('main')
  <div class="publisher-home">
    <div class="publisher-body">
      <h3>Sono stati trovati {{ $query->count()+$query2->count() }} risultati con la parola '{{$slug}}'</h3>
      <div class="col-lg-12">
        @if($query->count())
        <div class="row" id="queries">
          @foreach($query as $value)
          <div class="col-lg-3 col-sm-4 col-xs-12">
            <a href="{{ url($value->slug)}}">
              <div class="card">
                <img class="card-img-top" src="{{asset($value->getAvatar())}}" alt="Card image cap">
                @if($value->topic_id)
                <span>{{ $value->topic_name }}</span>
                @endif
                <div class="card-body">
                  <a href="{{url($value->slug)}}">
                    <h4>{{ $value->name }} {{ $value->surname }}</h4>
                  </a>
                </div>
              </div>
            </a>
          </div>
          @endforeach
        </div>
        @endif
        @if($query2->count())
        <h3>Articoli</h3>
        <div class="row" id="queries">
          @foreach($query2 as $value)
          <div class="col-lg-3 col-sm-12 col-xs-12">
            <a href="{{ url('read/'.$value->article_slug)}}">
              <div class="card">
                <img class="card-img-top" src="{{asset($value->getBackground())}}" alt="Card image cap">
                <div class="card-body">
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
              </div>
            </a>
          </div>
          @endforeach
        </div>
        @endif
        <script>
        App.insl('queries');
        </script>
      </div>
    </div>
  </div>
@endsection
