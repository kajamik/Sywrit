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
      <p>1. Inizi sin da subito a pubblicare articoli senza aspettare</p>
    </div>
    <div class="benefits-hero">
      <div class="hero hero-benefits-regis"></div>
      <span>2. E' una soluzione completamente gratuita</span>
    </div>
    <div class="benefits-hero">
      <div class="hero hero-benefits-ideas"></div>
      <span>3. Dai un valore alle tue idee</span>
    </div>
  </div>
  <hr/>
</div>
@endsection
