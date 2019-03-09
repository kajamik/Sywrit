@extends('tools.layout.app')

@section('title', 'Schermata Pagine -')

@section('main')
<div class="container">
  <form action="" method="POST">
    <div class="form-group">
      <div class="col-md-6">
        <input type="text" class="form-control" placeholder="Cerca per id, nome pagina, ...">
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
        <th>ID Pagina</th>
        <th>Nome Pagina</th>
        <th>Avvisi</th>
        <th>Pagina sospesa</th>
        <th>Pagina disattivata</th>
        <th>Azione</th>
      </tr>
      @foreach($query as $value)
      <tr>
        <td>{{ $value->id }}</td>
        <td>{{ $value->nome }}</td>
        <td>{{ $value->avvisi }}</td>
        <td>FALSO</td>
        <td>FALSO</td>
        <td><a href="{{ url('toolbox/pages/'.$value->id.'/sheet') }}"<button class="btn btn-info btn-block" role="button">Scheda</button></td>
      </tr>
      @endforeach
    </table>
  </div>
</div>
@endsection
