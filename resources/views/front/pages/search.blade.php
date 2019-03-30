@extends('front.layout.app')

@section('title', $slug.' -')

@section('main')
<div class="container">
  <h3>Sono stati trovati {{count($query)}} risultati con la parola '{{$slug}}'</h3>
  <div class="col-lg-12">
    @if(count($query))
    <div class="row" id="queries">
      @foreach($query as $value)
      @if(!empty($value->article_title))
      <div class="col-lg-3 col-sm-12 col-xs-12">
        <a href="{{ url('read/'.$value->article_slug)}}">
          <div class="card">
            <img class="card-img-top" src="{{asset($value->getBackground())}}" alt="Card image cap">
            <div class="card-body">
              <h4 class="card-title">{{ $value->article_title }}</h4>
              <div class="author">
                Pubblicato da
                  <a href="{{ url($value->user_slug) }}">
                    <span>{{ $value->user_name }} {{ $value->user_surname }}</span>
                  </a>
              </div>
            </div>
          </div>
        </a>
      </div>
      @else
      <div class="col-lg-3 col-sm-4 col-xs-12">
        <a href="{{ url($value->slug)}}">
          <div class="card">
            <img class="card-img-top" src="{{asset($value->getAvatar())}}" alt="Card image cap">
            <div class="card-body">
              <a href="{{url($value->slug)}}">
                <span>{{ $value->nome }} {{ $value->cognome }}</span>
              </a>
            </div>
          </div>
        </a>
      </div>
      @endif
      @endforeach
      </div>
      <script>
      App.insl('queries');
      </script>
      <hr/>
      @endif
  </div>
</div>
@endsection
