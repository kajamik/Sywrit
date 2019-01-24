@extends('front.layout.app')

@section('main')
<div class="container">
  <div class="publisher-home">
    <section class="publisher-header" style="background-image: url({{asset($query->getBackground())}})">
      <div class="container">
        <img class="publisher-logo" src="{{asset($query->getLogo())}}" alt="Logo">
        <div class="info">
          <span>{{$query->nome}} {{$query->cognome}}</span>
        </div>
      </div>
    </section>
    <section class="publisher-body">
      <li>
        <a href="{{ url('group/'.$query->slug) }}">Home</a>
        <a class="@if($tab == 'edit') active @endif" href="{{ url('group/'.$query->slug.'/settings/edit') }}">Modifica Pagina</a>
        <a class="@if($tab == 'role') active @endif" href="{{ url('group/'.$query->slug.'/settings/role') }}">Gestione ruoli</a>
      </li>
      <div class="container">
        @include('front.pages.group.tabs.'.$tab)
      </div>
    </section>
  </div>
</div>
@endsection
