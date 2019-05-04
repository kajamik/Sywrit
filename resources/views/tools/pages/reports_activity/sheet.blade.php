@extends('tools.layout.app')

@section('title', 'Segnalazione '. $sql->report_token)

@section('main')
<div class="container">
  <h2>Informazioni segnalazione</h2>
  <p>Token Segnalazione: {{ $sql->report_token }}</p>
  <p>Tipo segnalazione: {{ $sql->getReportName() }}</p>
  <p>Motivo segnalazione: @if($sql->report_text) {!! $sql->report_text !!} @else { Nessuna } @endif</p>
  <p>
    @if(!$sql->resolved)
    <span class="fa fa-lock-open"></span> <a id="lock" href="#sp">Chiudi segnalazione</a>
    @else
    <span class="fa fa-lock"></span> Segnalazione chiusa
    @endif
  </p>

  <h2>Informazioni oggetto</h2>
  @if($sql->article_id)
    <p>Id Articolo: {{ $query->id }}</p>
    <p>Titolo articolo segnalato: <a href="{{ url('read/'. $query->slug) }}">{{ $query->titolo }}</a></p>
    @if($query->id_gruppo)
    <p>Pubblicato da: <a href="{{ url('toolbox/publishers/'. $query->getRedazione->id. '/sheet') }}" target="_blank">{{ $query->getRedazione->name }}</a></p>
    @endif
    <p>Scritto da: <a href="{{ url('toolbox/users/'. $query->getAutore->id. '/sheet') }}" target="_blank">{{ $query->getAutore->name }} {{ $query->getAutore->surname }}</a></p>
    <p><a id="delete" href="#delete">Elimina articolo</a></p>

    <p>Contenuto:</p>
    <div class="block-body">
      {!! $query->testo !!}
    </div>
  @elseif($sql->reported_id)
    <p>Id Utente: {{ $query->id }}</p>
    <p>Nome utente segnalato: <a href="{{ url('toolbox/users/'. $query->id .'/sheet')}}" target="_blank">{{ $query->name }} {{ $query->surname }}</a></p>
  @elseif($sql->comment_id)
    <p>Id Commento: {{ $query2->id }}</p>
    <p>Autore: <a href="{{ url('toolbox/users/'. $query2->getUserInfo->id. '/sheet') }}" target="_blank">{{ $query2->getUserInfo->name }} {{ $query2->getUserInfo->surname }}</a></p>
    <p>Contenuto:</p>
    <div class="block-body">
      {!! $query2->text !!}
    </div>
  @endif
</div>

<script>
$("#lock").click(function(){
  App.getUserInterface({
    "ui": {
      "header":{"action": "{{ url('toolbox/reports_activity/lock_report') }}", "method": "GET"},
      "data":{"_token": "{{ $sql->report_token }}"},
      "title": "Gestione report",
      "content": [
        {"type": ["h5"], "text": "Stai per chiudere questa segnalazione. Vuoi procedere?"},
        {"type": ["input","submit"], "class": "btn btn-danger btn-block", "value": "Procedi"}
      ],
      "done": function(data){
        App.getUserInterface({
          "ui": {
            "title": "Gestione report",
            "content": [
              {"type": ["h5"], "text": data.message},
            ]
          }
        });
        window.location.reload(false);
      }
    }
  });
});
</script>
@endsection
