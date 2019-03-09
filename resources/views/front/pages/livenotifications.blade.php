@foreach($query as $value)
  <a class="dropdown-item" href="#">
    <div class="container">
      Nuova richiesta di collaborazione dalla redazione <strong>{{ $value->getEditorName->nome }}</strong>
    </div>
  </a>
@endforeach
<a class="dropdown-item" href="{{ url('read/id-nome-articolo') }}">
  <div class="container">
    Nuovo articolo <strong>%nome articolo%</strong>
  </div>
</a>
