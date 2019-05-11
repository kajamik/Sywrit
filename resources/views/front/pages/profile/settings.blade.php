@extends('front.layout.app')

@section('main')

  <div class="publisher-home">
    <div class="publisher-header" style="background-image: url({{asset(Auth::user()->getBackground())}})">
      <div class="container">
        <div class="publisher-logo">
          <div class="row">
            <div class="d-inline">
              <img src="{{ asset(Auth::user()->getAvatar()) }}" alt="Logo">
            </div>
            <div class="col-lg-10 col-sm-col-xs-12">
              <div class="mt-2 info">
                <span>{{ Auth::user()->name }} {{ Auth::user()->surname }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="publisher-body pt-3">
      <div class="container">
        <form method="post" action="{{route('settings')}}" enctype="multipart/form-data">
          @csrf
          <div class="form-group row">
            <label for="name" class="col-md-4 col-form-label text-md-right required">Nome</label>
            <div class="col-md-6">
              <input type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ Auth::user()->name }}">
              @if($errors->has('name'))
                  <span class="invalid-feedback" role="alert">
                      <strong>{{ $errors->first('name') }}</strong>
                  </span>
              @endif
            </div>
          </div>
          <div class="form-group row">
            <label for="surname" class="col-md-4 col-form-label text-md-right required">Cognome</label>
            <div class="col-md-6">
              <input type="text" class="form-control{{ $errors->has('surname') ? ' is-invalid' : '' }}" name="surname" value="{{ Auth::user()->surname }}">
              @if($errors->has('surname'))
                  <span class="invalid-feedback" role="alert">
                      <strong>{{ $errors->first('surname') }}</strong>
                  </span>
              @endif
            </div>
          </div>
          <hr/>
          <div class="form-group row">
            <label for="cover" class="col-md-4 col-form-label text-md-right">Immagine di copertina</label>
            <div class="col-md-6" id="cover">
              <label for="file-upload" class="form-control custom-upload{{ $errors->has('cover') ? ' is-invalid' : '' }}">
                <span class="fa fa-cloud-upload-alt"></span> Carica file
              </label>
              <input id="file-upload" type="file" onchange="App.upload(this.nextElementSibling, false)" name="cover">
              @if($errors->has('cover'))
                  <span class="invalid-feedback" role="alert">
                      <strong>{{ $errors->first('cover') }}</strong>
                  </span>
              @endif
              <div id="preview_cover" class="preview_body"></div>
            </div>
          </div>

          <div class="form-group row">
            <label for="avatar" class="col-md-4 col-form-label text-md-right">Immagine di profilo</label>
            <div class="col-md-6" id="avatar">
              <label for="file-upload2" class="form-control custom-upload{{ $errors->has('avatar') ? ' is-invalid' : '' }}">
                <span class="fa fa-cloud-upload-alt"></span> Carica file
              </label>
              <input id="file-upload2" type="file" onchange="App.upload(this.nextElementSibling)" name="avatar">
              @if($errors->has('avatar'))
                  <span class="invalid-feedback" role="alert">
                      <strong>{{ $errors->first('avatar') }}</strong>
                  </span>
              @endif
              <div id="preview_avatar" class="preview_body"></div>
            </div>
          </div>

          {{--@if(Auth::user()->avatar)
          <div class="form-group row">
            <div class="col-md-6 text-md-right">
              <a href="#photo">Elimina foto di profilo</a>
            </div>
          </div>
          @endif--}}

          <div class="form-group row">
            <label for="bio" class="col-md-4 col-form-label text-md-right">Biografia</label>

            <div class="col-md-6">
              <textarea id="bio" class="form-control{{ $errors->has('bio') ? ' is-invalid' : '' }}" name="bio" placeholder="Inserisci la tua biografia">{!! Auth::user()->biography !!}</textarea>
              @if($errors->has('bio'))
                  <span class="invalid-feedback" role="alert">
                      <strong>{{ $errors->first('bio') }}</strong>
                  </span>
              @endif
            </div>
          </div>
          <hr/>

          <div class="form-group row">
            <label for="facebook" class="col-md-4 col-form-label text-md-right">Facebook</label>

            <div class="col-md-6">
              <input id="facebook" type="text" class="form-control{{ $errors->has('facebook') ? ' is-invalid' : '' }}" name="facebook" value="{{ Auth::user()->facebook }}" placeholder="profilo facebook">
              @if($errors->has('facebook'))
                  <span class="invalid-feedback" role="alert">
                      <strong>{{ $errors->first('facebook') }}</strong>
                  </span>
              @endif
            </div>
          </div>

          <div class="form-group row">
            <label for="instagram" class="col-md-4 col-form-label text-md-right">Instagram</label>

            <div class="col-md-6">
              <input id="instagram" type="text" class="form-control{{ $errors->has('instagram') ? ' is-invalid' : '' }}" name="instagram" value="{{ Auth::user()->instagram }}" placeholder="profilo instagram">
              @if($errors->has('instagram'))
                  <span class="invalid-feedback" role="alert">
                      <strong>{{ $errors->first('instagram') }}</strong>
                  </span>
              @endif
            </div>
          </div>

          <div class="form-group row">
            <label for="linkedin" class="col-md-4 col-form-label text-md-right">Linkedin</label>

            <div class="col-md-6">
              <input id="linkedin" type="text" class="form-control{{ $errors->has('linkedin') ? ' is-invalid' : '' }}" name="linkedin" value="{{ Auth::user()->linkedin }}" placeholder="profilo linkedin">
              @if($errors->has('linkedin'))
                  <span class="invalid-feedback" role="alert">
                      <strong>{{ $errors->first('linkedin') }}</strong>
                  </span>
              @endif
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
        {{--
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
          </form> <!-- End Form --> --}}
          <hr/>
          <hr/>
          <form method="post" action="{{ route('settings/password') }}">
          @csrf
            <div class="form-group row">
              <label for="old_password" class="col-md-4 col-form-label text-md-right">Vecchia Password</label>
              <div class="col-md-6">
                <input id="old_password" type="password" class="form-control{{ $errors->has('old_password') ? ' is-invalid' : '' }}" name="old_password">
                @if ($errors->has('old_password'))
                  <span class="invalid-feedback" role="alert">
                      <strong>{{ $errors->first('old_password') }}</strong>
                  </span>
                @endif
              </div>
            </div>
            <div class="form-group row">
              <label for="password" class="col-md-4 col-form-label text-md-right">Nuova Password</label>
              <div class="col-md-6">
                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password">
                @if ($errors->has('password'))
                  <span class="invalid-feedback" role="alert">
                      <strong>{{ $errors->first('password') }}</strong>
                  </span>
                @endif
              </div>
            </div>
            <div class="form-group row">
              <label for="password_confirmation" class="col-md-4 col-form-label text-md-right">Conferma Password</label>
              <div class="col-md-6">
                <input id="password_confirmation" type="password" class="form-control{{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}" name="password_confirmation">
                @if ($errors->has('password_confirmation'))
                  <span class="invalid-feedback" role="alert">
                      <strong>{{ $errors->first('password_confirmation') }}</strong>
                  </span>
                @endif
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
          <form method="get" action="{{ url('account_delete') }}">
            @csrf
            @if(Auth::user()->suspended == 0)
            <div class="form-group row">
              <div class="col-md-6 offset-md-5">
                <button class="btn btn-link" name="button">Elimina account</button>
              </div>
            </div>
            @endif
          </form>
        </div>
      </div>
    </div>
@endsection
