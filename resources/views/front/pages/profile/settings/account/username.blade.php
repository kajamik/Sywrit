@php
  SEOMeta::setTitle('Indirizzo utente - Sywrit', false);
@endphp

<form method="post" action="{{ url('settings/account/username') }}" enctype="multipart/form-data">
  @csrf

  <div class="text-center">
    <p>Il nome utente è l'indirizzo del tuo profilo</p>
  </div>
  <div class="form-group row">
    <div class="col-md-3">
      <input type="text" class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}" name="username" value="{{ Auth::user()->slug }}" autofocus>
      @if($errors->has('username'))
          <span class="invalid-feedback" role="alert">
              <strong>{{ $errors->first('username') }}</strong>
          </span>
      @endif
    </div>
  </div>
  @if(empty(Auth::user()->social_auth_id))
  <div class="form-group row">
    <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('label.account.password_confirm') }}</label>
    <div class="col-md-6">
      <input type="password" class="form-control{{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}" name="password_confirmation" value="">
      @if($errors->has('password_confirmation'))
          <span class="invalid-feedback" role="alert">
              <strong>{{ $errors->first('password_confirmation') }}</strong>
          </span>
      @endif
    </div>
  </div>
  @endif
  <div class="form-group row">
    <div class="col-md-6 offset-md-4">
      <button type="submit" class="btn btn-info btn-block">
        Modifica nome utente
      </button>
    </div>
  </div>
</form>
