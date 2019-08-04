@extends('tools.layout.app')

@section('title', 'Utenti')

@section('main')
<div class="container">

  <div class="table_users">
    <table>
      <tr>
        <th>Nome</th>
        <th>Cognome</th>
        <th>Account sospeso</th>
        <th>Azione</th>
      </tr>
      @foreach($query as $value)
      <tr>
        <td>{{ $value->name }}</td>
        <td>{{ $value->surname }}</td>
        <td>@if($value->suspended) Si @else No @endif</td>
        <td><a href="{{ url('toolbox/users/'.$value->id.'/sheet') }}"<button class="btn btn-info btn-block" role="button">Scheda</button></td>
      </tr>
      @endforeach
    </table>
  </div>
</div>
@endsection
