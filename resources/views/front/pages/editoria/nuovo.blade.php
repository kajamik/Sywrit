@extends('front.layout.app')

@section('main')
<div class="container">
  <form method="POST" action="{{ route('register') }}">
    @csrf

    <div class="from-group row">
      <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Nome Editoria') }}</label>

      <div class="col-md-6">
          <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required autofocus>

          @if ($errors->has('name'))
              <span class="invalid-feedback" role="alert">
                  <strong>{{ $errors->first('name') }}</strong>
              </span>
          @endif
      </div>
    </div>
    <div class="from-group row">
      <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Categoria') }}</label>

      <div class="col-md-6">
        <input list="categorie" class="form-control" name="browser">
        <datalist id="categorie">
          <option value="moda">
            <option value="motori">
            <option value="videogiochi">
        </datalist>

          @if ($errors->has('name'))
              <span class="invalid-feedback" role="alert">
                  <strong>{{ $errors->first('name') }}</strong>
              </span>
          @endif
      </div>
    </div>

    <div class="form-group row mb-0">
        <div class="col-md-6 offset-md-4">
            <button type="submit" class="btn btn-primary">
                {{ __('Avanti') }}
            </button>
        </div>
    </div>
  </form>
</div>
@endsection
