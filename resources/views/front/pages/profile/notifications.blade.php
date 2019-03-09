@extends('front.layout.app')

@section('title', 'Notifiche -')

@section('main')

<style type="text/css">
.message {
  padding: 15px;
  margin-bottom: 20px;
  border: .5px solid #23488B;
}
.new-notification {
  background-color: #eee;
}
.message-content {
  font-size: 23px;
}
.message-response {
  margin-top: 5px;
  margin-left: 50px;
}
</style>

<div class="container">
  <div class="publisher-home">
    <section class="publisher-header" style="background-image: url({{asset(\Auth::user()->getBackground())}})">
      <div class="container">
        <img class="publisher-logo" src="{{asset(\Auth::user()->getAvatar())}}" alt="Logo">
        <div class="info">
          <span>{{\Auth::user()->nome}} {{\Auth::user()->cognome}}</span>
        </div>
      </div>
    </section>
    <section class="publisher-body">
      <h2>Notifiche</h2>
      @if(count($query))
      @foreach($query as $value)
      @php
        $value->marked = '1';
        $value->save();
      @endphp
      <div class="message @if(!$value->marked) new-notification @endif">
        <div class="message-content">
          {!! $value->text !!}
        </div>
        <div class="message-response">
          <button class="btn btn-success">{{__('Accetta')}}</button>
          <button class="btn btn-danger">{{__('Rifiuta')}}</button>
        </div>
      </div>
      @endforeach
      @else
        <p>Non hai notifiche da visualizzare</p>
      @endif
    </section>
  </div>
</div>
@endsection
