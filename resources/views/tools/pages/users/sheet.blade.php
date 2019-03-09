@extends('tools.layout.app')

@section('title', 'Schedario '.$query->id.' -')

@section('main')
<div class="container">
  <h1>Informazioni utente</h1>
  <p>Nome: {{ $query->nome }}</p>
  <p>Cognome: {{ $query->cognome }}</p>
  <p>Numero di volta avvisato: {{ $query->avvisi }}</p>
  <p>Segnalazioni ricevute: </p>

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
</div>
@endsection
