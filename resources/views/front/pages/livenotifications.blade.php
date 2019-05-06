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
  @php
    $publisher_request = \DB::table('publisher_request')->find($value->content_id);
    $publisher = \DB::table('editori')->find($publisher_request->publisher_id);
  @endphp
  <a class="dropdown-item" href="{{ url('notifications#'.$value->id) }}">
    <div class="container">
      Nuova richiesta di collaborazione dalla redazione <strong>{{ $publisher->name }}</strong>
    </div>
  </a>
  @endif
  @if($value->type == '2') {{-- Valutazione Articolo --}}
  <a class="dropdown-item" href="{{ url('notifications#'.$value->id) }}">
    <div class="container">
      Il tuo articolo <strong>{{ $articolo->titolo }}</strong> ha ricevuto una valutazione.
    </div>
  </a>
  @endif
  @if($value->type == '2') {{-- Valutazione Articolo --}}
  <a class="dropdown-item" href="{{ url('notifications#'.$value->id) }}">
    <div class="container">
      Il tuo articolo <strong>{{ $articolo->titolo }}</strong> ha ricevuto una valutazione.
    </div>
  </a>
  @endif
@endforeach
@else
  <div class="container">
    Nessuna notifica da visualizzare
  </div>
@endif
