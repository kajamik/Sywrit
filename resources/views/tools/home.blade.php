@extends('tools.layout.app')

@section('title', 'Dashboard')

@section('main')
<div class="container">
  <h3>Bentornato nel tuo pannello, {{ Auth::user()->nome }}. Cosa vuoi fare oggi ?</h3>

  <div class="row">

    <div class="col-xl-5 col-lg-6 col-md-6 col-sm-6">
      <div class="card">
        <div class="card-header card-header-warning">
          Riepilogo generali
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-9">
              <h3 class="card-category">Utenti registrati</h3>
            </div>
            <h3 class="card-category">{{ $users }}</h3>
          </div>
          <div class="row">
            <div class="col-9">
              <h3 class="card-category">Articoli creati</h3>
            </div>
            <h3 class="card-category">{{ $articles }}</h3>
          </div>
          <div class="row">
            <div class="col-9">
              <h3 class="card-category">Articoli pubblicati</h3>
            </div>
            <h3 class="card-category">{{ $published_articles }}</h3>
          </div>
          <div class="row">
            <div class="col-9">
              <h3 class="card-category">Articoli segnalati</h3>
            </div>
            <h3 class="card-category">0</h3>
          </div>
        </div>
        </div>
      </div>

    <div class="col-xl-5 col-lg-6 col-md-6 col-sm-6">
      <div class="card">
        <div class="card-header card-header-warning">
          Riepilogo commenti
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-9">
              <h3 class="card-category">Commenti creati</h3>
            </div>
            <h3 class="card-category">0</h3>
          </div>
          <div class="row">
            <div class="col-9">
              <h3 class="card-category">Risposte inviate</h3>
            </div>
            <h3 class="card-category">0</h3>
          </div>
        </div>
        </div>
      </div>

      <div class="col-xl-5 col-lg-6 col-md-6 col-sm-6">
        <div class="card">
          <div class="card-header card-header-warning">
            Riepilogo utenti
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-9">
                <h3 class="card-category">Utenti creati</h3>
              </div>
              <h3 class="card-category">0</h3>
            </div>
            <div class="row">
              <div class="col-9">
                <h3 class="card-category">Articoli pubblicati</h3>
              </div>
              <h3 class="card-category">0</h3>
            </div>
          </div>
          </div>
        </div>

      <div class="col-xl-5 col-lg-6 col-md-6 col-sm-6">
        <div class="card">
          <div class="card-header card-header-warning">
            Riepilogo redazioni
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-9">
                <h3 class="card-category">Redazioni create</h3>
              </div>
              <h3 class="card-category">0</h3>
            </div>
            <div class="row">
              <div class="col-9">
                <h3 class="card-category">Articoli pubblicati</h3>
              </div>
              <h3 class="card-category">0</h3>
            </div>
          </div>
          </div>
        </div>

    </div>

</div>
@endsection
