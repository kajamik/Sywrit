@extends('front.layout.app')

@section('title', 'Gestione Ticket - Assistenza -')
@section('styles')
  <link href="{{ asset('css/support.css') }}" rel="stylesheet" />
@endsection

@section('main')
<div class="container rc">
    <div class="col-12 top-background" style="background-image: url({{url('upload/bg-top.png')}})">
      <h3 id="title-shop">Gestione Ticket</h3>
    </div>

    <div class="support-directory">
      <nav>
        <ul>
          <li><i class="fas fa-life-ring"></i> <a href="{{url('/support')}}">Centro Assistenza</a></li>
          <li>Gestione Ticket</li>
        </ul>
      </nav>
    </div>

  @auth
    @if(Auth::user()->permission > 0)
      @include('front.components.support.management')
    @endif
  @endauth
</div>
@endsection
