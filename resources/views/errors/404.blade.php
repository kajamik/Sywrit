@extends('front.layout.app')

@section('title', 'Pagina non trovata -')

@section('main')
<style>
.container {
  padding: 15px;
  background-color: #fff;
}
.container article {
  padding: 12px;
}
</style>
<div class="container">
  <h1>Ops... qualcosa è andato storto</h1>
    <article>
      <img src="{{asset('upload/errors/220px-Friedrich_Ruckert.jpg')}}" alt="">
      <blockquote style="display:inline-block;vertical-align:top;font-size:28px;">“O speranze che non passano e desideri che non tornano indietro!
        Solo le quiete sensazioni del presente sono la felicità.” Friedrich Rückert</blockquote>
    </article>
</div>
@endsection
