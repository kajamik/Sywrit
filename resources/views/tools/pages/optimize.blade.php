@extends('tools.layout.app')

@section('title', 'Ottimizza il server')

@section('main')
<div class="container">

<style>
.form-control {
  color: #000 !important;
}
.console {
  width: 100%;
  height: 300px;
  background-color: #000;
  color: #fff;
}
</style>
  {{-- Alert session --}}
  @if(Session::has('output'))
  <div class="alert alert-info">
    <p>{!! Session::get('output') !!}</p>
  </div>
  @endif

  <div class="bg-light p-3">
    <h3>Console</h3>

    <hr/>

    <div class="form-group p-2">
      <div class="col-12">
        <input id="command" type="text" class="form-control" placeholder="Inserisci un commando" />
      </div>
    </div>

    <hr/>

    <div class="form-group">
      <div class="col-12">
        <button type="button" class="btn btn-primary btn-block" onclick="run();">
          @lang('Avvia commando')
        </button>
      </div>
    </div>

    <div class="form-group">
      <div class="col-12">
        <div class="console p-2 overflow-auto">
          <medium>{!! $root !!}: In attesa di un commando...</medium>
        </div>
      </div>
  </div>

  <form action="{{ url('toolbox/optimize') }}" method="post">
    @csrf
    <div class="bg-light p-3">

      <h3>Libera lo spazio da file inutili</h3>

      <hr/>

      <div class="form-group p-2">
        <h3>Cache</h3>
        <div class="col-12">
          <label for="cache">Svuota le cache del server</label>
          <input id="cache" type="checkbox" name="cache" />
        </div>
        <div class="col-12">
          <label for="route">Svuota le cache del route</label>
          <input id="route" type="checkbox" name="route" />
        </div>
        <div class="col-12">
          <label for="view">Svuota le cache delle view</label>
          <input id="view" type="checkbox" name="view" />
        </div>
      </div>

      <hr/>

      <div class="form-group p-2">
        <h3>Immagini</h3>
        <div class="col-12">
          <label for="users">Elimina le immagini utente (avatar e cover)</label>
          <input id="users" type="checkbox" name="users" />
        </div>
        <div class="col-12">
          <label for="article">Elimina le immagini articoli</label>
          <input id="article" type="checkbox" name="articles" />
        </div>
      </div>

      <hr/>

      <div class="form-group">
        <div class="col-12">
          <button type="submit" class="btn btn-primary btn-block">
            @lang('Pulisci file')
          </button>
        </div>
      </div>
    </div>
  </form>

  <script>
  var dir = "{!! $root !!}:";
  var _console = $(".console");
  function run() {
    var cmd = $("#command").val();
    if(cmd.length > 0) {
      _console.find(":last-child").text(dir + ": " +cmd);
      // Before
      App.query("get", "{{ url('toolbox/server/console/execute') }}", {cmd: cmd}, false, function(o) {
        $("<br/>").appendTo(_console);
        $("<medium style='color:green;'>"+ o.string +"</medium>").appendTo(_console);
        $("<br/>").appendTo(_console);
        var item = $("<medium>" + dir + ": in attesa di un commando...</medium>").appendTo(_console);
        _console.animate({
          scrollTop: item.offset().top
        });
      });
    } else {
      alert("Input non valido");
    }
  }
  </script>
</div>
</div>
@endsection
