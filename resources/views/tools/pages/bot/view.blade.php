@extends('tools.layout.app')

@section('title', 'Bot Message')

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
    <table>
      <tr>
        <th>ID messaggio</th>
        <th>Titolo</th>
        <th>Azione</th>
      </tr>
      @foreach($query as $value)
      <tr>
        <td>{{ $value->id }}</td>
        <td>{{ $value->title }}</td>
        <td><a href="{{ url('toolbox/bot_message/'.$value->id.'/message') }}"<button class="btn btn-info btn-block" role="button">Scheda</button></td>
      </tr>
      @endforeach
    </table>
  </div>
</div>
@endsection
