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
        <hr/>
        <div class="form-group row">
          <label for="cover" class="col-md-4 col-form-label text-md-right">Immagine di copertina</label>
          <div class="col-md-6" id="cover">
            <label for="file-upload" class="form-control custom-upload">
              <i class="fa fa-cloud-upload-alt"></i> Carica file
            </label>
            <input id="file-upload" type="file" onchange="App.upload(this.parentNode, false)" name="cover">
          </div>
        </div>
        <div class="form-group row">
          <label for="avatar" class="col-md-4 col-form-label text-md-right">Immagine di profilo</label>
          <div class="col-md-6" id="avatar">
            <label for="file-upload2" class="form-control custom-upload">
              <i class="fa fa-cloud-upload-alt"></i> Carica file
            </label>
            <input id="file-upload2" type="file" onchange="App.upload(this.parentNode)" name="avatar">
          </div>
        </div>

        {{--<div class="form-group row">
          <label for="bio" class="col-md-4 col-form-label text-md-right">Descrizione</label>

          <div class="col-md-6">
            <textarea id="bio" class="form-control" name="bio">{{ Auth::user()->bio }}</textarea>
          </div>
        </div>--}}
        <hr/>

        <div class="form-group row">
          <label for="facebook" class="col-md-4 col-form-label text-md-right">Facebook</label>

          <div class="col-md-6">
            <input id="facebook" type="text" class="form-control" name="facebook" value="{{ Auth::user()->facebook }}"placeholder="profilo facebook">
          </div>
        </div>

        <div class="form-group row">
          <label for="instagram" class="col-md-4 col-form-label text-md-right">Instagram</label>

          <div class="col-md-6">
            <input id="instagram" type="text" class="form-control" name="instagram" value="{{ Auth::user()->instagram }}" placeholder="profilo instagram">
          </div>
        </div>

        <div class="form-group row">
          <label for="linkedin" class="col-md-4 col-form-label text-md-right">Linkedin</label>

          <div class="col-md-6">
            <input id="linkedin" type="text" class="form-control" name="linkedin" value="{{ Auth::user()->linkedin }}" placeholder="profilo linkedin">
          </div>
        </div>

        <div class="form-group row">
          <div class="col-md-6 offset-md-4">
            <button type="submit" class="btn btn-info btn-block">
              Salva Impostazioni
            </button>
          </div>
        </div>
      </form> <!-- End -->
        <hr/>
        <hr/>
        <form method="post" action="{{ route('settings/username') }}">
          @csrf
          <div class="form-group row">
            <label for="username" class="col-md-4 col-form-label text-md-right required">Nome utente</label>
            <div class="col-md-6">
              <span>{{url('/')}}/</span>
              <div style="display:inline-block;">
                <input type="text" class="form-control{{ $errors->has('slug') ? ' is-invalid' : '' }}" name="slug" value="{{\Auth::user()->slug}}">
              </div>
            </div>
            @if ($errors->has('slug'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('username') }}</strong>
                </span>
            @endif
          </div>
          <div class="form-group row">
            <label for="verification" class="col-md-4 col-form-label text-md-right required">Conferma Password</label>
            <div class="col-md-6">
                <input type="password" class="form-control{{ $errors->has('verification') ? ' is-invalid' : '' }}" name="verification" placeholder="Verifica">
            </div>
            @if ($errors->has('verification'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('verification') }}</strong>
                </span>
            @endif
          </div>
          <div class="form-group row">
            <div class="col-md-6 offset-md-4">
              <button type="submit" class="btn btn-info btn-block">
                Modifica nome utente
              </button>
            </div>
          </div>
        </form> <!-- End Form -->
        <hr/>
        <hr/>
        <form method="post" action="{{route('settings/password')}}">
          @csrf
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
            <label for="password_confirmation" class="col-md-4 col-form-label text-md-right">Conferma Password</label>
            <div class="col-md-6">
              <input id="password_confirmation" type="password" class="form-control" name="password_confirmation">
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-6 offset-md-4">
              <button type="submit" class="btn btn-info btn-block">
                Modifica password
              </button>
            </div>
          </div>
        </form>
    </section>
  </div>
</div>
@endsection
