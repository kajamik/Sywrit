@extends('tools.layout.app')

@section('title', 'Utente #'.$query->id)

@section('main')
<div class="container">
  <h1>Informazioni utente</h1>
  <p>Nome: {{ $query->name }}</p>
  <p>Cognome: {{ $query->surname }}</p>
  <p>Segnalazioni inviate: 0</p>
  <p>Segnalazioni ricevute: {{ $user_reports->count() }}</p>
  <p>
    @if(!$query->suspended)
    <span class="fa fa-lock-open"></span> <a id="lock" href="#sp">Sospendi account</a>
    @else
    <span class="fa fa-lock"></span> <a id="lock" href="#sp">Riattiva account</a>
    @endif
  </p>

  <hr/>
  <h2>Segnalazioni ricevute</h2>
  <div class="table_users">
    <table>
      <tr>
        <th>ID Articolo</th>
        <th>Nome articolo</th>
        <th>Autore Articolo</th>
        <th>Motivazione</th>
        <th>Testo Report</th>
        <th>Report risolto</th>
        <th>Azione</th>
      </tr>
    </table>
  </div>

  <hr/>
  <h2>Attività Segnalazioni</h2>
  <div class="table_users">
    <table>
      <tr>
        <th>ID Articolo</th>
        <th>Nome articolo</th>
        <th>Autore Articolo</th>
        <th>Motivazione</th>
        <th>Testo Report</th>
        <th>Report risolto</th>
        <th>Azione</th>
      </tr>
      @foreach($reports_activity as $value)
      @php
        $articolo = \App\Models\Articoli::where('id',$value->article_id)->first();
        $autore = \App\Models\User::where('id',$articolo->id_autore)->first();
      @endphp
      <tr>
        <td>{{ $articolo->id }}</td>
        <td>{{ $articolo->titolo }}</td>
        <td><a href="{{ url('toolbox/users/'.$autore->id.'/sheet')}}">{{ $autore->nome }} {{ $autore->cognome }}</a></td>
        <td>{{ $value->getReportName() }}</td>
        <td>{!! $value->report_text !!}</td>
        <td>@if($value->resolved) Si @else No @endif</td>
        <td><a href="#del">Cancella articolo</a></td>
        <td><a href="{{ url('read/'.$articolo->slug) }}" target="_blank">Vai all'articolo</a></td>
      </tr>
      @endforeach
    </table>
  </div>

  <script>
  $("#lock").click(function(){
    App.getUserInterface({
      "ui": {
        "header":{"action": "{{ url('toolbox/users/lock_account') }}", "method": "GET"},
        "data":{"id": "{{$query->id}}"},
        "title": "Gestione account",
        "content": [
          {"type": ["h5"], "text": "Stai per @if(!$query->suspended) chiudere @else riattivare @endif questo account. Vuoi procedere?"},
          {"type": ["input","submit"], "class": "btn btn-danger btn-block", "value": "Procedi"}
        ],
        "done": function(data){
          $(".__ui__g").remove();
          if(data.suspended == 0){
            $("#lock").prev().attr('class', 'fa fa-lock-open');
            $("#lock").text("Sospendi account");
          } else {
            $("#lock").prev().attr('class', 'fa fa-lock');
            $("#lock").text("Riattiva account");
          }
        }
      }
    });
  });
  </script>

</div>
@endsection
