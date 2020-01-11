@extends('tools.layout.app')

@isset($query)
  @section('title', 'Articolo #'.$query->id)
@else
  @section('title', 'Articolo inesistente')
@endisset

@section('main')
<div class="container">
  @isset($query)
  <h1>Informazioni articolo</h1>
  <p>ID Autore: <a href="{{ url('toolbox/users/'. $query->getAutore->id . '/sheet')}}">{{ $query->getAutore->id }}</a></p>
  <p>Titolo articolo: {{ $query->titolo }}</p>
  <p>Segnalazioni ricevute: </p>
  <p><button id="delete" type="button" class="btn btn-primary">Elimina articolo</button></p>
  <p>Contenuto:</p>
  <div class="article" style="color:#fff;">
    {!! $query->testo !!}
  </div>

  <script>
  $("#delete").click(function(){
    App.getUserInterface({
      "ui": {
        "header":{"action": "{{ url('toolbox/articles/delete') }}", "method": "POST"},
        "title": "Eliminazione articolo",
        "content": [
          {"type": ["input", "hidden"], "name": "_token", "value": "{{ csrf_token() }}"},
          {"type": ["input", "hidden"], "name": "id", "value": "{{ $query->id }}"},
          {"type": ["p"], "text": "Stai per eliminare questo articolo. Per favore, seleziona un'opzione"},
          {"type": ["input","radio"], "id": "selOption", "name": "option", "value": "0", "class": "col-md-1", "label": "Notizia Falsa", "required": true},
          {"type": ["input","radio"], "id": "selOption", "name": "option", "value": "1", "class": "col-md-1", "label": "Contenuto di natura sessuale", "required": true},
          {"type": ["input","radio"], "id": "selOption", "name": "option", "value": "2", "class": "col-md-1", "label": "Contenuto violento o che incita all\'odio", "required": true},
          {"type": ["input","radio"], "id": "selOption", "name": "option", "value": "3", "class": "col-md-1", "label": "Promuove il terrorismo o attività criminali", "required": true},
          {"type": ["input","radio"], "id": "selOption", "name": "option", "value": "4", "class": "col-md-1", "label": "Violazione del diritto d\'autore", "required": true},
          {"type": ["input","radio"], "id": "selOption", "name": "option", "value": "5", "class": "col-md-1", "label": "Spam", "required": true},
          {"type": ["textarea"], "id":"reasonText", "name": "reason", "value": "", "class": "form-control text-dark p-2", "placeholder": "Motiva la segnalazione (opzionale)"},
          {"type": ["input","submit"], "class": "btn btn-danger btn-block", "value": "Elimina definitivamente"}
        ]
      }
    }, true);
  });
  </script>
@else
  <p>Articolo inesistente</p>
@endisset
</div>
@endsection
