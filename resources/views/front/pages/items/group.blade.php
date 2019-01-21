@extends('front.layout.app')

@section('titolo','Registrazione - Crea la tua attività -')

@section('main')
<style type="text/css">
.form-group a {
  color: #00e;
  text-decoration: underline !important;
}
</style>

<div class="container">
  <div class="row justify-content-center">
      <div class="col-md-8">
          <div class="card">
              <div class="card-header">{{ __('Crea la tua attività - '.config('app.name')) }}</div>

              <div class="card-body">
                  <form method="POST" action="{{ route('offer/complete') }}">
                      @csrf
                      <input type="hidden" name="type" value="g">

                            <div class="form-group row">
                                <label for="nome" class="col-md-4 col-form-label text-md-right required">{{ __('Nome Gruppo') }}</label>
                                <div class="col-md-6">
                                    <input id="nome" type="text" class="form-control{{ $errors->has('group_name') ? ' is-invalid' : '' }}" name="nome" required>
                                    @if ($errors->has('nome'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('nome') }}</strong>
                                        </span>
                                    @endif
                                </div>
                              </div>
                              <div class="form-group row">
                                  <label for="cat" class="col-md-4 col-form-label text-md-right">{{ __('Categoria') }}</label>
                                  <div class="col-md-6">
                                    <input list="cat" class="form-control{{ $errors->has('cat') ? ' is-invalid' : '' }}" name="cat">
                                      <datalist id="cat">
                                        <option value="Moda ed Estetica">
                                        <option value="Informatica ed Elettronica">
                                        <option value="Videogiochi">
                                      </datalist>
                                      @if ($errors->has('cat'))
                                          <span class="invalid-feedback" role="alert">
                                              <strong>{{ $errors->first('cat') }}</strong>
                                          </span>
                                      @endif
                                  </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-12">
                                      <input id="checkbox" type="checkbox">
                                      <label for="checkbox">
                                        Proseguendo si accettano le nostre <a href="{{url('legal/terms')}}">Condizioni</a>.
                                      </label>
                                      @if ($errors->has('terms'))
                                          <span class="invalid-feedback" role="alert">
                                              <strong>{{ $errors->first('terms') }}</strong>
                                          </span>
                                      @endif
                                  </div>
                                </div>

                                <div class="form-group row">
                                  <div class="col-md-6 offset-md-6">
                                    <button class="btn btn-info">Termina registrazione</button>
                                  </div>
                                </div>
                    </div>
                </form>
              </div>
          </div>
      </div>
</div>
@endsection
