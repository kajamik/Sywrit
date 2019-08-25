<a href="{{ url('auth/facebook/redirect') }}">
  <button type="button" class="btn btn-facebook btn-block">
      <i class="fab fa-facebook-f"></i>
      @lang('button.login_with_facebook')
  </button>
</a>

<form method="POST" action="{{ route('register') }}">
    @csrf

    <div class="form-group row">
        <label for="name" class="col-md-12 col-form-label text-center">@lang('label.account.name')</label>

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
        <label for="surname" class="col-md-12 col-form-label text-center">@lang('label.account.surname')</label>

        <div class="col-md-12">
            <input id="surname" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="surname" value="{{ old('surname') }}" required autofocus>

            @if ($errors->has('surname'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('surname') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group row">
        <label for="email" class="col-md-12 col-form-label text-center">@lang('label.account.email_address')</label>

        <div class="col-md-12">
            <input id="email" type="text" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>

            @if ($errors->has('email'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group row">
        <label for="password" class="col-md-12 col-form-label text-center">@lang('label.account.password')</label>

        <div class="col-md-12">
            <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" value="{{ old('password') }}" required autofocus>
        </div>
    </div>

    <div class="form-group row">
        <label for="password-confirm" class="col-md-12 col-form-label text-center">@lang('label.account.password_confirm')</label>

        <div class="col-md-12">
            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autofocus>
        </div>
    </div>

    <div class="form-group">
      <div class="col-md-12">
        <label>@lang('label.terms_message')</label>
      </div>
    </div>

    <div class="form-group row">
        <div class="col-md-12">
            <button type="submit" class="btn btn-sw btn-block">
                @lang('button.register')
            </button>
        </div>
    </div>

    <div class="form-group row">
        <div class="col-md-12">
          <button id="scene" type="button" class="btn btn-link">
            @lang('button.already_registered')
          </button>
        </div>
      </div>

</form>

<script>
$("#scene").on('click', function() {
  $.get("{{ url('ajax/auth') }}", {callback: 'auth_login'}, function(data) {
    $(".__ui__g_body").html(data);
  });
});
</script>
