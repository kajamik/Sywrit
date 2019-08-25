<form method="post" action="{{ url('settings/change_password') }}">
@csrf
  <div class="form-group row">
    <label for="old_password" class="col-md-4 col-form-label text-md-right">{{ __('label.account.old_password') }}</label>
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
    <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('label.account.new_password') }}</label>
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
    <label for="password_confirmation" class="col-md-4 col-form-label text-md-right">{{ __('label.account.password_confirm') }}</label>
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
        {{ __('button.password_changes') }}
      </button>
    </div>
  </div>
</form>
