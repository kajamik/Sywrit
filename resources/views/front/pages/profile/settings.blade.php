@extends('front.layout.app')

@section('title', 'Impostazioni Profilo -')

@section('main')

<div class="container">
  <div class="publisher-home">
    <section class="publisher-header" style="background-image: url({{asset(\Auth::user()->getBackground())}})">
      <div class="container">
        <img class="publisher-logo" src="{{asset(\Auth::user()->getAvatar())}}" alt="Logo">
        <div class="info">
          <span>{{\Auth::user()->nome}} {{\Auth::user()->cognome}}</span>
        </div>
      </div>
    </section>
    <section class="publisher-body">
      <form method="post" action="{{route('settings')}}" enctype="multipart/form-data">
        @csrf
        <div class="form-group row">
          <label for="name" class="col-md-4 col-form-label text-md-right required">Nome</label>
          <div class="col-md-6">
            <input type="text" class="form-control" name="name" value="{{\Auth::user()->nome}}">
          </div>
        </div>
        <div class="form-group row">
          <label for="surname" class="col-md-4 col-form-label text-md-right required">Cognome</label>
          <div class="col-md-6">
            <input type="text" class="form-control" name="surname" value="{{\Auth::user()->cognome}}">
          </div>
        </div>
        <div class="form-group row">
          <label for="birthdate" class="col-md-4 col-form-label text-md-right required">Data di nascita</label>
          <div class="col-md-6">
            <input id="birthdate" type="date" class="form-control" name="birthdate" value="{{\Auth::user()->birthdate}}">
          </div>
        </div>
        <hr/>
        <div class="form-group row">
          <label for="cover" class="col-md-4 col-form-label text-md-right">Immagine di copertina</label>
          <div class="col-md-6">
            <label for="file-upload" class="form-control custom-upload">
              <i class="fa fa-cloud-upload-alt"></i> Carica file
            </label>
            <input id="file-upload" type="file" name="cover">
            <div id="image_preview" class="preview"></div>
          </div>
        </div>
        <div class="form-group row">
          <label for="avatar" class="col-md-4 col-form-label text-md-right">Immagine di profilo</label>
          <div class="col-md-6">
            <label for="file-upload2" class="form-control custom-upload">
              <i class="fa fa-cloud-upload-alt"></i> Carica file
            </label>
            <input id="file-upload2" type="file" name="avatar">
            <div id="avatar_preview" class="preview"></div>
          </div>
        </div>
        <hr/>
        <div class="form-group row">
          <label for="old_password" class="col-md-4 col-form-label text-md-right">Vecchia Password</label>
          <div class="col-md-6">
            <input id="old_password" type="password" class="form-control" name="old_password">
          </div>
        </div>
        <div class="form-group row">
          <label for="password" class="col-md-4 col-form-label text-md-right">Nuova Password</label>
          <div class="col-md-6">
            <input id="password" type="password" class="form-control" name="password">
          </div>
        </div>
        <div class="form-group row">
          <label for="confirm_password" class="col-md-4 col-form-label text-md-right">Conferma Password</label>
          <div class="col-md-6">
            <input id="confirm_password" type="password" class="form-control" name="confirm_password">
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
    </section>
  </div>
</div>

{{--<script>
App.upIm({'settings':{'bg': 'cover','pr': 'avatar'}});
</script>--}}
@endsection
