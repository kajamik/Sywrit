@extends('front.layout.app')

@section('main')
@php

  $str = array(
    "Nick Hornby" => "Se si desidera che la lettura sopravviva come attività di svago, allora dobbiamo fare pubblicità alle gioie che ci regala, più che ai suoi dubbi benefici.",
    "Friedrich Ruckert" => "O speranze che non passano e desideri che non tornano indietro! Solo le quiete sensazioni del presente sono la felicità.",
    "Peter Ferdinand Drucker" => "Dietro ogni impresa di successo c'è qualcuno che ha preso una decisione coraggiosa.",
  );

  $n = array_rand($str,1);
@endphp
<style>
.container article {
  padding: 26px;
}
</style>
  <div class="publisher-home">
    <div class="publisher-body">
      <h1>Ops... qualcosa è andato storto</h1>
      <article>
        <blockquote style="display:inline-block;vertical-align:top;font-size:28px;">
            <q>{{ $str[$n] }}</q>
            <address>{{ $n }}</address>
        </blockquote>
        </article>
      </div>
    </div>
@endsection
