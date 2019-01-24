@foreach($editori as $editore)
<hr/>
<div id="editor">
<h5>{{$editore->nome}}</h5>
  @php
    $articoli = \App\Models\Articoli::where('id_gruppo',$editore->id)->get();
  @endphp
  <div class="col-lg-12">
    <div class="row">
      @foreach($articoli as $value)
      @php
        $autore = \DB::table('Utenti')->where('id',$value->autore)->first();
      @endphp
      <div class="col-lg-3 col-sm-8 col-xs-12">
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
  </div>
</div>
@endforeach
