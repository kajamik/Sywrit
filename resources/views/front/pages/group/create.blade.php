@extends('front.layout.app')

@section('main')
<div class="container">
  <h1>{{ config('app.name')}}: Scegli una delle due opzioni</h1>
  <div class="block-box">
    <div class="block-title">
      <h1>Gruppo</h1>
      <p>Ti piace condividere le tue idee con i tuoi amici?</p>
    </div>
    <div class="block-body">
      <div id="benefits">
        <h3>Puoi</h3>
        <p>Creare la tua community</p>
        <p>Gestire la tua editoria</p>
        <p>Assumere collaboratori</p>
        <hr/>
        <h3>Caratteristiche</h3>
        <p>6 utenti</p>
        <p>Supporto (E-mail,Ticket)</p>
      </div>
      <div id="price">
        <p>Prezzo:</p>
        <h3>Gratuito</h3>
      </div>
    </div>
    <div class="block-footer">
      <a href="{{ url('start/offer/?link_id=1') }}" class="btn btn-primary">
        Seleziona
      </a>
    </div>
  </div>
  <div class="block-box">
    <div class="block-title">
      <h1>Individuale</h1>
      <p>Sei uno scrittore o un blogger? Oppure vuoi semplicemente pubblicare le tue idee?</p>
    </div>
    <div class="block-body">
      <div id="benefits">
        <h3>Puoi</h3>
        <p>Creare la tua community</p>
        <p class="disabled">Gestire la tua editoria</p>
        <p class="disabled">Assumere collaboratori</p>
        <hr/>
        <h3>Caratteristiche</h3>
        <p>1 utente</p>
        <p>Supporto (E-mail,Ticket)</p>
      </div>
      <div id="price">
        <p>Prezzo:</p>
        <h3>Gratuito</h3>
      </div>
    </div>
    <div class="block-footer">
      <a href="{{ url('start/offer/?link_id=2') }}" class="btn btn-primary">
        Seleziona
      </a>
    </div>
  </div>
</div>
@endsection
