@extends('front.layout.app')

@section('titolo','Termini di servizio - Crea la tua attività - ')

@section('main')
<div class="container">
  <div class="row justify-content-center">
      <div class="col-md-8">
          <div class="card">
              <div class="card-header">{{ __('Termini di servizio - Modulo 1 di 2') }}</div>

              <div class="form-reader">
                <p>
                  <h1>Termini di servizio</h1>
                  <h2>1.Paragrafo</h2>
                  <p>Testo.................</p>
                  <h2>2.Paragrafo</h2>
                  <p>Testo.................</p>
                  <h2>3.Paragrafo</h2>
                  <p>Testo.................</p>
                </p>
              </div>
              <hr/>

              <div class="col-md-6">
                <input id="checkbox" type="checkbox">
                <label for="checkbox">Comprendo e accetto i termini di servizio
              </div>

              <div class="form-group row">
                <div class="col-md-6 offset-md-4">
                  <button class="btn btn-info">Termina registrazione</button>
                </div>
              </div>
          </div>
      </div>
  </div>
</div>
@endsection
