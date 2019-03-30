@if($query->count())
@foreach($query as $value)
  @php
    if($value->marked == '0'){
      $value->marked = '1';
      $value->save();
    }
    if(!empty($value->content_id)){
      $articolo = \App\Models\Articoli::where('id',$value->content_id)->first();
    }
  @endphp
  @if($value->type == '1') {{-- Collaborazione --}}
  <a class="dropdown-item" href="{{ url('notifications#'.$value->id) }}">
    <div class="container">
      Nuova richiesta di collaborazione dalla redazione <strong>{{ $value->getPublisherName->nome }}</strong>
    </div>
  </a>
  @endif
  @if($value->type == '2' || $value->type == '3') {{-- Utente --}}
  <a class="dropdown-item" href="{{ url('read/'.$articolo->slug) }}">
    <div class="container">
      Nuovo articolo <strong>{{ $articolo->titolo }}</strong>
    </div>
  </a>
  @endif
@endforeach
@else
  <div class="container">
    Nessuna notifica da visualizzare
  </div>
@endif
