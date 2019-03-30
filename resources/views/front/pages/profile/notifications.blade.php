@extends('front.layout.app')

@section('title', 'Notifiche -')

@section('main')

<style type="text/css">
#nav > li {
  display: inline-block;
  margin-top: 5px;
}
#nav > li:not(:last-child)::after {
  content: '\00a0|';
}
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
    <div class="publisher-header" style="background-image: url({{asset(\Auth::user()->getBackground())}})">
      <div class="container">
        <div class="publisher-logo">
          <img src="{{asset(\Auth::user()->getAvatar())}}" alt="Logo">
        </div>
        <div class="info">
          <span>{{\Auth::user()->nome}} {{\Auth::user()->cognome}}</span>
        </div>
      </div>
    </div>
      <div class="publisher-body">
        <ul id="nav">
          <li><a href="{{url(\Auth::user()->slug)}}">Home</a></li>
          <li><a href="{{url(\Auth::user()->slug.'/about')}}">Informazioni</a></li>
          <li><a href="{{url(\Auth::user()->slug.'/archive')}}">Articoli Salvati</a></li>
        </ul>
        <hr/>
        <h2>Notifiche</h2>
        @if($query->count())
        <a href="#" onclick="delAll()">Cancella tutto</a>
        <div class="notifications">
          @foreach($query as $value)
          @php
            if($value->marked == '0'){
              $value->marked = '1';
              $value->save();
            }
            if(!empty($value->content_id)){
              $articolo = \App\Models\Articoli::where('id',$value->content_id)->first();
            }
            @endphp
            @if($value->type == '1') {{-- Collaborazione --}}
            <div id="noty_{{ $value->id }}" class="message">
              <div class="message-content">
                <div class="message-close float-right">&times;</div>
                <div class="message-info">
                  <time>{{ $value->created_at }}</time>
                </div>
                <div class="my-3 pl-3">
                  Nuova richiesta di collaborazione dalla redazione <strong>{{ $value->getPublisherName->nome }}</strong>
                  <div class="actions">
                    <button id="acceptRequest_{{ $value->id }}" class="btn btn-primary" type="role">
                      Accetta
                    </button>
                    <script>
                    $("#acceptRequest_{{ $value->id }}").click(function(){
                      App.query('get','{{ url("request_accepted") }}', {id: {{ $value->id }}}, false, function(data){
                        $("#noty_{{ $value->id }}").html("<h5>Richiesta Accettata</h5>");
                        $("#noty_{{ $value->id }}").fadeOut(2000);
                      });
                    });
                  </script>
                  </div>
                </div>
              </div>
            </div>
            @else
            <div id="{{ $value->id }}" class="message">
              <div class="message-content">
                <h2><a href="{{ url('read/'.$articolo->slug) }}">{{ $articolo->titolo }}</a></h2>
                <p>Pubblicato da
                  @if($value->type == '2')
                  <span><a href="{{ url($value->getUserName->slug) }}">{{ $value->getUserName->nome }} {{ $value->getUserName->cognome }}</a></span>
                  @else
                  <span><a href="{{ url($value->getPublisherName->slug) }}">{{ $value->getPublisherName->nome }}</a></span>
                  @endif
                </p>
              </div>
            </div>
            @endif
            @endforeach
          </div>
          @else
          <p>Nessuna notifica da visualizzare</p>
          @endif
        </div>
    </div>
</div>
@if($query->count())
<script>
function delAll() {
  App.query('get','{{ url("notifications_delete") }}', null, false, function(data){
      $(".notifications *").fadeOut(function(){
        $(".notifications").html("<h5>Nessuna notifica da visualizzare</h5>");
      });
  });
}
</script>
@endif
@endsection
