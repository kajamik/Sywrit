<a href="{{ url('auth/facebook/redirect') }}">
  <button type="button" class="btn btn-facebook btn-block">
      <i class="fab fa-facebook-f"></i>
      {{ __('Accedi con Facebook') }}
  </button>
</a>

<form method="POST" action="{{ route('login') }}">
    @csrf

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
        <div class="col-md-6 offset-md-4">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember" checked>

                <label class="form-check-label" for="remember">
                    {{ __('Ricordami') }}
                </label>
            </div>
        </div>
    </div>

    <div class="form-group row">
        <div class="col-md-12">
            <button type="submit" class="btn btn-sw btn-block">
                {{ __('Accedi') }}
            </button>

            @if (Route::has('password.request'))
                <a class="btn btn-primary btn-block" href="{{ route('password.request') }}">
                    {{ __('Hai dimenticato i dati di accesso?') }}
                </a>
            @endif
        </div>
    </div>

    <div class="form-group row">
        <div class="col-md-12">
          <button id="scene" type="button" class="btn btn-link">
            {{ __('Non hai ancora un account? Creane uno') }}
          </button>
        </div>
      </div>
</form>

<script>
$("#scene").on('click', function() {
  $.get("{{ url('ajax/auth') }}", {callback: 'auth_register'}, function(data) {
    $(".__ui__g_body").html(data);
  });
});
</script>
