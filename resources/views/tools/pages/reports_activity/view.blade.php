@extends('tools.layout.app')

@section('title', 'Attività segnalazioni')

@section('main')
<div class="container">
  <hr/>
  <div class="table_users">
    <table>
      <tr>
        <th>Token</th>
        <th>Report di</th>
        <th>Motivazione report</th>
        <th>Azione</th>
      </tr>
      @foreach($query as $value)
      <tr>
        <td>{{ $value->report_token }}</td>
        <td>{{ $value->getAutore->name }} {{ $value->getAutore->surname }}</td>
        <td>{{ $value->report_text }}</td>
        <td><a href="{{ url('toolbox/reports_activity/view?_token='.$value->report_token)}}"><button class="btn btn-info" role="button">Scheda</button></a></td>
      </tr>
      @endforeach
    </table>
    <div style="margin-top:50px;display:flex;justify-content:center;">
      {{ $query->links() }}
    </div>
  </div>
</div>
@endsection
