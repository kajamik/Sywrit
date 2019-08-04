@extends('tools.layout.app')

@section('title', 'Bot Message')

@section('main')
<div class="container">

  <div class="col-md-2">
    <a href="{{ url('toolbox/bot_message/create') }}">
      <button class="btn btn-info" role="button"><i class="fas fa-plus"></i> Crea messaggio</button>
    </a>
  </div>

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
