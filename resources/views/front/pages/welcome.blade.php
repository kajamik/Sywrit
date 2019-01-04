@extends('front.layout.app')

@section('titolo', 'Area Personale -')

@section('main')
<div class="container">
  <h5>Consigliati</h5>
  @if(!empty($consigliati))
  <div class="col-lg-12">
    <div class="row">
      @foreach($consigliati as $articolo)
        @php
          $editore = \App\User::where('id',$articolo->autore)->first();
          if(!empty($editore->id_gruppo))
           $editore = \App\Models\Editori::where('id',$articolo->editoria)->first();
          @endphp
          <div class="col-lg-2 col-sm-6 col-xs-12">
            <a href="{{ url('read/'.$articolo->id.'-'.$articolo->slug)}}">
              <div class="card">
                <img class="card-img-top" src="{{asset($articolo->getBackground())}}" alt="Card image cap">
                <div class="card-body">
                  <h5 class="card-title">{{ $articolo->titolo }}</h5>
                  <p class="card-text">{!! str_limit(strip_tags($articolo->testo), 15) !!}</p>
                  <a href="{{url('publisher/'.$editore->slug)}}"><span>{{$editore->nome}}</span></a>
                </div>
              </div>
            </a>
          </div>
          @endforeach
        </div>
      </div>
    @endif
  <hr/>
</div>
@endsection
