@extends('front.layout.app')

@section('title', 'Eliminazione Account - ')

@section('main')
  <div class="publisher-home">
    <div class="publisher-body">
      <h1>Eliminazione Account</h1>

      <form method="POST" action="{{ url('account_delete') }}">
        @csrf
        <h3>Se deciderai di eliminare l'account avrai 30 giorni per recuperarlo, prima che tutti i contenuti vengano eliminati definitivamente.</h3>

        <div class="row">

          <div class="col-lg-12">
            <div class="card">
              <div class="card-header">Contenuto</div>
              <div class="card-body">
                <div class="d-flex">
                  <p>Verranno eliminati tutti i tuoi contenuti, ad esclusione degli articoli di redazione. Attualmente hai <strong>{{ $articoli->count() }}</strong> articoli e <strong>{{ $feedback->count() }}</strong> commenti.</p>
                  <div class="float-right pl-5">
                    <i class="fas fa-box fa-5x"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>

          <div class="group-row">
            <div class="col-md-12">
              <button type="submit" class="btn btn-danger">Elimina Account</button>
            </div>
          </div>
        </div>

      </form>

    </div>
  </div>
@endsection
