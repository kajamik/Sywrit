@extends('front.layout.app')

@section('title', $slug.' -')

@section('main')
<div class="container">
  <h3>Sono stati trovati {{count($query)}} risultati con la parola '{{$slug}}'</h3>
  <div class="col-lg-12">
    @if(count($query))
    <div class="row">
      @foreach($query as $value)
      @if(!empty($value->titolo))
      @php
        $autore = \DB::table('Utenti')->where('id',$value->autore)->first();
      @endphp
      <div class="col-lg-3 col-sm-4 col-xs-12">
        <a href="{{ url('read/'.$value->slug)}}">
          <div class="card">
            <img class="card-img-top" src="{{asset($value->getBackground())}}" alt="Card image cap">
            <div class="card-body">
              <h5 class="card-title">{{ $value->titolo }}</h5>
              <a href="{{url($autore->slug)}}"><span>{{ $autore->nome }} {{ $autore->cognome }}</span></a>
            </div>
          </div>
        </a>
      </div>
      @else
      <div class="col-lg-3 col-sm-4 col-xs-12">
        <a href="{{ url($value->slug)}}">
          <div class="card">
            <img class="card-img-top" src="{{asset($value->getBackground())}}" alt="Card image cap">
            <div class="card-body">
              <a href="{{url($value->slug)}}"><span>{{ $value->nome }} {{ $value->cognome }}</span></a>
            </div>
          </div>
        </a>
      </div>
      @endif
      @endforeach
      </div>
      <hr/>
      @endif
  </div>
</div>
@endsection
