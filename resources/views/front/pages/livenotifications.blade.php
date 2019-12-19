@if($query->count())
@foreach($query as $value)
  @php
    if($value->read == '0'){
      $value->read = '1';
      $value->save();
    }
    if(!empty($value->content_id)){
      $articolo = \App\Models\Articoli::where('id', $value->content_id)->first();
    }
  @endphp
  @if($value->type == '1') {{-- Collaborazione --}}
  @php
    $publisher_request = \DB::table('publisher_request')->find($value->content_id);
    $publisher = \DB::table('editori')->find($publisher_request->publisher_id);
  @endphp
    <div class="container p-2">
      Nuova richiesta di collaborazione dalla redazione <strong>{{ $publisher->name }}</strong>
    </div>
  @endif
  @if($value->type == '2') {{-- Valutazione Articolo --}}
    <div class="container p-2">
      Il tuo articolo <strong>{{ $articolo->titolo }}</strong> ha ricevuto una valutazione.
    </div>
  @endif
  @if($value->type == '2') {{-- Valutazione Articolo --}}
    <div class="container p-2">
      Il tuo articolo <strong>{{ $articolo->titolo }}</strong> ha ricevuto una valutazione.
    </div>
  @endif
@endforeach
@else
  <div class="container p-2">
    @lang('label.notifications.no_content')
  </div>
@endif
