@extends('tools.layout.app')

@section('title', 'Dashboard')

@section('main')
<div class="container">
  <h3>Bentornato nel tuo pannello</h3>

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
            <h3 class="card-category">{{ $archs+$articles }}</h3>
          </div>
          <div class="row">
            <div class="col-9">
              <h3 class="card-category">Articoli pubblicati</h3>
            </div>
            <h3 class="card-category">{{ $articles }}</h3>
          </div>
          <div class="row">
            <div class="col-9">
              <h3 class="card-category">Articoli segnalati</h3>
            </div>
            <h3 class="card-category">{{ $reported_articles }}</h3>
          </div>
        </div>
        </div>
      </div>

    <div class="col-xl-5 col-lg-6 col-md-6 col-sm-6">
      <div class="card">
        <div class="card-header card-header-warning">
          Riepilogo social
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-9">
              <h3 class="card-category">Reazioni inviate</h3>
            </div>
            <h3 class="card-category">{{ $reactions }}</h3>
          </div>
          <div class="row">
            <div class="col-9">
              <h3 class="card-category">Commenti creati</h3>
            </div>
            <h3 class="card-category">{{ $comments }}</h3>
          </div>
          <div class="row">
            <div class="col-9">
              <h3 class="card-category">Risposte inviate</h3>
            </div>
            <h3 class="card-category">{{ $answers }}</h3>
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
              <h3 class="card-category">{{ $users }}</h3>
            </div>
            <div class="row">
              <div class="col-9">
                <h3 class="card-category">Utenti in eliminazione</h3>
              </div>
              <h3 class="card-category">{{ $cron_users }}</h3>
            </div>
            <div class="row">
              <div class="col-9">
                <h3 class="card-category">Articoli pubblicati</h3>
              </div>
              <h3 class="card-category">{{ $articles }}</h3>
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
              <h3 class="card-category">{{ $publishers }}</h3>
            </div>
            <div class="row">
              <div class="col-9">
                <h3 class="card-category">Articoli pubblicati</h3>
              </div>
              <h3 class="card-category">{{ $publisher_articles }}</h3>
            </div>
          </div>
          </div>
        </div>

    </div>

</div>
@endsection
