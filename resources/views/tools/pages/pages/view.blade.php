@extends('tools.layout.app')

@section('title', 'Editori')

@section('main')
<div class="container">

  <div class="table_users">
    <table>
      <tr>
        <th>ID Pagina</th>
        <th>Nome Pagina</th>
        <th>Pagina sospesa</th>
        <th>Azione</th>
      </tr>
      @foreach($query as $value)
      <tr>
        <td>{{ $value->id }}</td>
        <td>{{ $value->name }}</td>
        <td>@if($value->suspended) Si @else No @endif</td>
        <td><a href="{{ url('toolbox/publishers/'.$value->id.'/sheet') }}"<button class="btn btn-info btn-block" role="button">Scheda</button></td>
      </tr>
      @endforeach
    </table>
  </div>
</div>
@endsection
