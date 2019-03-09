@extends('front.layout.app')

@section('title', $slug.' -')

@section('main')
<div class="container">
  <h3>Sono stati trovati {{count($query)+count($query2)}} risultati con la parola '{{$slug}}'</h3>
  <div class="col-lg-12">
    @if(count($query))
    <div class="row">
      @foreach($query as $value)
      @php
        $autore = \DB::table('Utenti')->where('id',$value->autore)->first();
      @endphp
      <div class="col-lg-3 col-sm-4 col-xs-12">
        <a href="{{ url('read/'.$value->slug)}}">
          <div class="card">
            <img class="card-img-top" src="{{asset($value->getBackground())}}" alt="Card image cap">
            <div class="card-body">
              <h5 class="card-title">{{ $value->titolo }}</h5>
              <a href="{{url($autore->slug)}}"><span>{{$autore->nome}} {{$autore->cognome}}</span></a>
            </div>
          </div>
        </a>
      </div>
      @endforeach
      </div>
      <hr/>
      @endif
      @if(count($query2))
      <div class="row">
      @foreach($query2 as $value)
      <div class="col-lg-2 col-sm-4 col-xs-12">
        <a href="{{ url($value->slug)}}">
          <div class="card">
            <img class="card-img-top" src="{{asset($value->getAvatar())}}" alt="Card image cap">
            <div class="card-body">
              <p class="card-title">{{ $value->nome }} {{ $value->cognome }}</p>
            </div>
          </div>
        </a>
      </div>
      @endforeach
    </div>
    @endif
  </div>
</div>
@endsection
