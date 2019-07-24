<a href="{{ url('auth/facebook/redirect?to='.$redirectTo) }}">
  <button type="button" class="btn btn-facebook btn-block">
      <i class="fab fa-facebook-f"></i>
      {{ __('Accedi con Facebook') }}
  </button>
</a>

<form method="POST" action="{{ route('login') }}">
    @csrf

    <div class="form-group row">
        <label for="name" class="col-md-12 col-form-label text-center">{{ __('Nome') }}</label>

        <div class="col-md-12">
            <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('email') }}" required autofocus>

            @if ($errors->has('name'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('name') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group row">
        <label for="email" class="col-md-12 col-form-label text-center">{{ __('Indirizzo email') }}</label>

        <div class="col-md-12">
            <input id="surname" type="text" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="surname" value="{{ old('email') }}" required autofocus>

            @if ($errors->has('surname'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('surname') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group row">
        <label for="email" class="col-md-12 col-form-label text-center">{{ __('Indirizzo email') }}</label>

        <div class="col-md-12">
            <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>

            @if ($errors->has('email'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group row">
        <label for="email" class="col-md-12 col-form-label text-center">{{ __('Password') }}</label>

        <div class="col-md-12">
            <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" value="{{ old('password') }}" required autofocus>

            @if ($errors->has('password'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group row">
        <div class="col-md-12">
            <button type="submit" class="btn btn-sw btn-block">
                {{ __('Registrati') }}
            </button>
        </div>
    </div>

    <div class="form-group row">
        <div class="col-md-12">
          <button id="scene" type="button" class="btn btn-link">
            {{ __('Hai già un account? Effettua l\'accesso') }}
          </button>
        </div>
      </div>

</form>

<script>
$("#scene").on('click', function() {
  $.get("{{ url('ajax/auth') }}", {path: '{{ Request::path() }}', callback: 'auth_login'}, function(data) {
    $(".__ui__g_body").html(data);
  });
});
</script>
