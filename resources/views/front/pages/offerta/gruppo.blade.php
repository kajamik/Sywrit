@extends('front.layout.app')

@section('titolo','Registrazione - Crea la tua attività -')

@section('main')
<div class="container">
  <div class="row justify-content-center">
      <div class="col-md-8">
          <div class="card">
              <div class="card-header">{{ __('Crea la tua attività - Modulo 1 di 2') }}</div>

              <div class="card-body">
                  <form method="POST" action="{{ route('offer') }}">
                      @csrf

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
                              <label for="birthday" class="col-md-4 col-form-label text-md-right required">{{ __('Data di nascita') }}</label>
                              <div class="col-md-6">
                                  <input id="birthday" type="date" class="form-control{{ $errors->has('birthday') ? ' is-invalid' : '' }}" name="birthday">
                                  @if ($errors->has('email'))
                                      <span class="invalid-feedback" role="alert">
                                          <strong>{{ $errors->first('email') }}</strong>
                                      </span>
                                  @endif
                              </div>
                            </div>
                            <hr/>
                            <div class="form-group row">
                                <label for="group_name" class="col-md-4 col-form-label text-md-right required">{{ __('Nome Gruppo') }}</label>
                                <div class="col-md-6">
                                    <input id="group_name" type="text" class="form-control{{ $errors->has('group_name') ? ' is-invalid' : '' }}" name="group_name" autofocus>
                                    @if ($errors->has('group_name'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('group_name') }}</strong>
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
                                  <div class="col-md-6 offset-md-6">
                                    <button class="btn btn-info">Avanza >></button>
                                  </div>
                                </div>
                    </div>
                </form>
              </div>
          </div>
      </div>
</div>
@endsection
