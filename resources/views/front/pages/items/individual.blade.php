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
                      <input type="hidden" name="type" value="i">

                      <div class="form-group row">
                          <label for="name" class="col-md-4 col-form-label text-md-right required">{{ __('Nome Completo') }}</label>
                          <div class="col-md-6">
                              <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" autofocus>
                              @if ($errors->has('name'))
                                  <span class="invalid-feedback" role="alert">
                                      <strong>{{ $errors->first('name') }}</strong>
                                  </span>
                              @endif
                          </div>
                        </div>
                          <div class="form-group row">
                              <label for="date" class="col-md-4 col-form-label text-md-right required">{{ __('Data di nascita') }}</label>
                              <div class="col-md-6">
                                  <input id="date" type="date" class="form-control{{ $errors->has('date') ? ' is-invalid' : '' }}" name="date">
                                  @if ($errors->has('date'))
                                      <span class="invalid-feedback" role="alert">
                                          <strong>{{ $errors->first('date') }}</strong>
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
