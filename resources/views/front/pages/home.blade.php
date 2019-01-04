@extends('front.layout.app')

@section('main')
<div class="block-hero">
  <div class="container">
    <div class="caption">
      <span>
        <h1>slogan</h1>
        <a href="{{ route('register') }}">
          <button class="btn btn-info">
            Registrati subito
          </button>
        </a>
      </span>
    </div>
  </div>
</div>
<hr/>

<div class="container">
  <div class="block-benefits">
    <h2>I vantaggi</h2>
    <div class="benefits-hero">
      <div class="hero hero-benefits-books"></div>
      <p>1. Inizi sin da subito a pubblicare articoli, senza dover spendere soldi.</p>
    </div>
    <div class="benefits-hero">
      <div class="hero hero-benefits-regis"></div>
      <span>2. Puoi decidere se aprire un'editoria, oppure se diventare un collaboratore.</span>
    </div>
  </div>
  <hr/>
</div>
@endsection
