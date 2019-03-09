@extends('tools.layout.app')

@section('title', 'Schedario '.$query->id.' -')

@section('main')
<div class="container">
  <h1>Informazioni Pagina</h1>
  <p>Nome: {{ $query->nome }}</p>
  <p>Numero di volta avvisato: {{ $query->avvisi }}</p>
  <p>Numero di volte segnalata: {{ $reports_activity->count() }}</p>

  <button class="btn btn-info" onclick="suspended_page()">Sospendi pagina</button>
  <button class="btn btn-info">Disattiva pagina</button>

  <h2>Attività Segnalazioni</h2>
  <div class="table_users">
    <table tabindex="-1">
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
        $autore = \App\Models\User::where('id',$articolo->autore)->first();
      @endphp
      <tr>
        <td>{{ $articolo->id }}</td>
        <td>{{ $articolo->titolo }}</td>
        <td><a href="{{ url('toolbox/users/'.$autore->id.'/sheet')}}">{{ $autore->nome }} {{ $autore->cognome }}</a></td>
        <td>{{ $value->getReportName() }}</td>
        <td>{!! $value->report_text !!}</td>
        <td>Si</td>
        <td><a href="{{ url('read/'.$articolo->slug) }}" target="_blank"><button class="btn btn-info" role="button">Vai all'articolo</button></a></td>
      </tr>
      @endforeach
    </table>
  </div>

<script type="text/javascript">
  function suspended_page() {
    App.getUserInterface({
      "ui": {
        "title": "Sospensione Pagina",
        "content": [

        ]
      }
    })
  }
</script>
</div>
@endsection
