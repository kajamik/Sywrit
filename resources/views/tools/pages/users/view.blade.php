@extends('tools.layout.app')

@section('title', 'Utenti')

@section('main')
<div class="container">
  <form action="" method="POST">
    <div class="form-group">
      <div class="col-md-6">
        <input type="text" class="form-control" placeholder="Cerca per id, nome, cognome, ...">
      </div>
    </div>
    <div class="form-group">
      <div class="col-md-2">
        <button class="btn btn-info" role="button">Filtra</button>
      </form>
    </div>
  </form>

  <div class="table_users">
    <table tabindex="-1">
      <tr>
        <th>Nome</th>
        <th>Cognome</th>
        <th>Avvisi</th>
        <th>Profilo sospeso</th>
        <th>Profilo disattivato</th>
        <th>Azione</th>
      </tr>
      @foreach($query as $value)
      <tr>
        <td>{{ $value->nome }}</td>
        <td>{{ $value->cognome }}</td>
        <td>{{ $value->avvisi }}</td>
        <td>FALSO</td>
        <td>FALSO</td>
        <td><a href="{{ url('toolbox/users/'.$value->id.'/sheet') }}"<button class="btn btn-info btn-block" role="button">Scheda</button></td>
      </tr>
      @endforeach
    </table>
  </div>
</div>
@endsection
