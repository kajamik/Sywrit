@extends('tools.layout.app')

@section('title', 'Schermata Pagine -')

@section('main')
<div class="container">
  <form action="" method="POST">
    <div class="form-group">
      <div class="col-md-6">
        <input type="text" class="form-control" placeholder="Cerca per id, tipo report, ...">
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
        <th>ID Report</th>
        <th>Tipo report</th>
        <th>Motivazione report</th>
        <th>Azione</th>
        <th></th>
      </tr>
      @foreach($query as $value)
      <tr>
        <td>{{ $value->id }}</td>
        <td>{{ $value->getReportName() }}</td>
        <td>{{ $value->report_text }}</td>
        <td><a id="sheet_{{ $value->id }}" href="#reports_activity"><button class="btn btn-info btn-block" role="button">Segna come letta</button></a></td>
        <td><button class="btn btn-info" role="button">Archivia</button></td>
      </tr>
      <script type="text/javascript">
      document.getElementById("sheet_{{ $value->id }}").addEventListener("click", function(){
        App.getUserInterface({
          "ui": {
            "title": "Schedario #{{ $value->id }}",
            "header": {"method": "POST"},
            "content": [
              {"type": ["h5"], "text": "Nome Report:"},
              {"type": ["p"], "text": "{{ $value->id }}"},
            ]
          }
        });
      });
      </script>
      @endforeach
    </table>
  </div>
</div>
@endsection
