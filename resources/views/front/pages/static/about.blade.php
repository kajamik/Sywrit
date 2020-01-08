@extends('front.layout.app')

@php
   SEOMeta::setTitle('Informazioni - Sywrit', false);
 @endphp

@section('main')
<style>
.img-rounded {
  border-radius: 17px;
}
.tl {
  position: relative;
}
.tl > .tl-pt::before {
  content: '';
  background: #000;
  display: inline-block;
  position: absolute;
  width: 2px;
  height: 100%;
  margin-left: -5px;
}
.tl > .tl-pt {

  padding-left: 25px;
}
.tl > .tl-pt > .tl-body {

}
</style>

<div class="publisher-home">
  <div class="publisher-body text-center">
    <section>

      <section>
        <h2>Sywrit: il nostro progetto</h2>
        <p>Sywrit è una piattaforma di scrittura, la quale permette di creare uno spazio blog in cui scrivere i propri articoli: condividere le proprie idee, passioni e molto altro.</p>
      </section>
      <section>
        <h2>Il nostro team</h2>
        <div class="row">
      		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
				   	<img class="col-lg-6 col-md-4 col-6 img-rounded" alt="Paolo Carpentras" src="https://www.sywrit.com/upload/team/carpentras.jpg">
				   	<h4>Paolo Carpentras</h4>
				   	<p>Fondatore e sviluppatore web</p>
            <p>Amante dell'informatica e della psicologia; attualmente studente di Digital Humanities all'Università di Pisa.</p>
      		 </div>
        </div>
    </section>

    </section>
  </div>
</div>
@endsection
