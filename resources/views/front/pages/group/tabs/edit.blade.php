@section('title', 'Impostazioni -')

<form method="post" action="" enctype="multipart/form-data">
  @csrf
  <div class="form-group row">
    <label for="name" class="col-md-4 col-form-label text-md-right required">Nome Gruppo</label>
    <div class="col-md-6">
      <input type="text" class="form-control" name="name" value="{{$query->nome}}">
    </div>
  </div>
  <div class="form-group row">
    <label for="_tp" class="col-md-4 col-form-label text-md-right">Tipo restrizione</label>
    <div class="col-md-6">
      <select id="_tp" class="form-control" name="_tp_sel">
        <option value="1" @if(!$query->type) selected @endif>Pubblicazione senza revisione</option>
        <option value="2" @if($query->type) selected @endif>Pubblicazione con revisione</option>
      </select>
    </div>
  </div>
  <div class="form-group row">
    <label for="background" class="col-md-4 col-form-label text-md-right">Immagine di copertina</label>
    <div class="col-md-6" id="background">
      <label for="cover-upload" class="form-control custom-upload">
        <i class="fa fa-cloud-upload-alt"></i> Carica file
      </label>
      <input id="cover-upload" type="file" onchange="App.upload(this.parentNode, false)" name="background">
    </div>
  </div>
  <div class="form-group row">
    <label for="logo" class="col-md-4 col-form-label text-md-right">Immagine di profilo</label>
    <div class="col-md-6" id="logo">
      <label for="avatar-upload" class="form-control custom-upload">
        <i class="fa fa-cloud-upload-alt"></i> Carica file
      </label>
      <input id="avatar-upload" type="file" onchange="App.upload(this.parentNode, false)" name="logo">
    </div>
  </div>
  <div class="form-group row">
    <div class="col-md-6 offset-md-4">
      <button type="submit" class="btn btn-info btn-block">
        Salva Impostazioni
      </button>
    </div>
  </div>
  </form>
  <hr/>
  <form method="post" action="{{ route('group/action/delete') }}">
    @csrf
    @if($query->accesso)
    <p class="text-center">Una volta disabilitata la pagina, questa verrà eliminata dopo 4 giorni di inattività</p>
    <div class="form-group row">
      <div class="col-md-6 offset-md-5">
        <button class="btn btn-danger">Disabilita pagina</button>
      </div>
    </div>
    @else
    <div class="form-group row">
      <div class="col-md-6 offset-md-5">
        <button class="btn btn-info">Riattiva pagina</button>
      </div>
    </div>
    @endif
  </form>
