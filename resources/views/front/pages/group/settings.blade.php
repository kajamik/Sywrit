@extends('front.layout.app')

@section('main')
<div class="container">
  <div class="publisher-home">
    <section class="publisher-header" style="background-image: url({{asset($query->getBackground())}})">
      <div class="container">
        <div class="publisher-logo">
          <img src="{{asset($query->getLogo())}}" alt="Logo">
        </div>
        <div class="info">
          <span>{{$query->nome}} {{$query->cognome}}</span>
        </div>
      </div>
    </div>
    <div class="publisher-body">
      <div class="container">
        <li>
          <a href="{{ url($query->slug) }}">Home</a>
          <a class="@if($tab == 'edit') active @endif" href="{{ url($query->slug.'/settings/edit') }}">Modifica gruppo</a>
          <a class="@if($tab == 'role') active @endif" href="{{ url($query->slug.'/settings/role') }}">Gestione ruoli</a>
        </li>
        <div class="container my-5">
          @include('front.pages.group.tabs.'.$tab)
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
