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
          if( !empty($articolo->id_gruppo) )
            $editore = \DB::table('Editori')->where('id',$articolo->id_gruppo)->first();
          else
            $editore = \DB::table('Utenti')->where('id',$articolo->autore)->first();
        @endphp
          <div class="col-lg-3 col-sm-8 col-xs-12">
            <a href="{{ url('read/'.$articolo->slug)}}">
              <div class="card">
                <img class="card-img-top" src="{{asset($articolo->getBackground())}}" alt="Card image cap">
                <div class="card-body">
                  <h4 class="card-title">{{ $articolo->titolo }}</h4>
                  @if( !empty($articolo->id_gruppo) )
                    <a href="{{url('group/'.$editore->slug)}}"><span>{{$editore->nome}}</span></a>
                  @else
                    <a href="{{url('profile/'.$editore->slug)}}"><span>{{$editore->nome}}</span></a>
                  @endif
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
