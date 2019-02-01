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
    <label for="background" class="col-md-4 col-form-label text-md-right">Immagine di copertina</label>
    <div class="col-md-6">
      <label for="cover-upload" class="form-control custom-upload">
        <i class="fa fa-cloud-upload-alt"></i> Carica file
      </label>
      <input id="cover-upload" type="file" name="background">
    </div>
  </div>
  <div class="form-group row">
    <label for="logo" class="col-md-4 col-form-label text-md-right">Immagine di profilo</label>
    <div class="col-md-6">
      <label for="avatar-upload" class="form-control custom-upload">
        <i class="fa fa-cloud-upload-alt"></i> Carica file
      </label>
      <input id="avatar-upload" type="file" name="logo">
    </div>
  </div>
  <div class="form-group row">
    <div class="col-md-6 offset-md-4">
      <button type="submit" class="btn btn-primary btn-block">
        Salva Impostazioni
      </button>
    </div>
  </div>
  </form>
  <hr/>
  <form method="post" action="{{ url(Request::url()) }}">
  <div class="form-group row">
    <div class="col-md-6 offset-md-5">
      <button class="btn btn-danger">Elimina gruppo</button>
    </div>
  </div>
</form>
