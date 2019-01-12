@extends('front.layout.app')

@section('title', 'Ticket - Assistenza -')

@section('styles')
  <link href="{{ asset('css/support.css') }}" rel="stylesheet" />
@endsection

@section('main')
<div class="container rc">
  <div class="col-12 top-background" style="background-image: url({{url('upload/bg-top.png')}})">
    <h3 id="title-shop">Centro Assistenza</h3>
  </div>
  <div class="support-directory">
    <nav>
      <ul>
        <li><i class="fas fa-life-ring"></i> <a href="{{url('/support')}}">Centro Assistenza</a></li>
        <li>Ticket: <strong>{{$ticket->name}}</strong></li>
      </ul>
    </nav>
  </div>

  @foreach($ticket_msg as $value)
    @php
      $user = App\Models\User::where('id',$value->id_user)->first();
    @endphp
    <div id="{{$value->id}}" class="ticket">
      <div class="ticket-card">
        @if($user->permission > 0)
        <span><img src="{{asset('upload/icons/staff.png')}}"></span>
        @else
        <span><img src="{{asset($user->getAvatar())}}"></span>
        @endif
        <div class="user-info">
          <span>{{$user->nome}} {{$user->surname}}</span>
          <span>{{$user->getRole()}}</span>
        </div>
      </div>
      <div class="ticket-content">
        {!! $value->text !!}
      </div>
    </div>
  @endforeach
  @if($ticket->status == 0) {{-- Se il topic è aperto --}}
  @if((Auth::user()->id == $last_message->id_user) && Auth::user()->permission == 0)
  <div>
    <h4>Attendi la risposta di un operatore</h4>
  </div>
  @else
    @include('front.components.support.answer')
  @endif
  @else
  <div>
    <h4>Questo ticket è stato chiuso da un operatore</h4>
  </div>
  @endif
</div>
@endsection
