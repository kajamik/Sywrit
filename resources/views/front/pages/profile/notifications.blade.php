@extends('front.layout.app')

@section('main')

<style type="text/css">
#nav > li {
  display: inline-block;
  margin-top: 5px;
  margin-bottom: 10px;
  font-size: 20px;
}
#nav > li:not(:last-child)::after {
  content: '\00a0|';
}
.message {
  margin-bottom: 20px;
  padding: 26px;
  border: .5px solid #eee;
}
.new-notification {
  background-color: #eee;
}
.message-title {
  padding: 5px 15px;
  border-bottom: 1px solid #bfb8eb;
}
.message-body {
  padding: 5px 16px;
  font-size: 23px;
}
.message-body a {
  color: #aaa;
}
.message-response {
  margin-top: 5px;
  margin-left: 50px;
}
.message-close {
  font-size: 20px;
  cursor: pointer;
}
</style>

  <div class="publisher-home">
    <div class="publisher-header" style="background-image: url({{asset(\Auth::user()->getBackground())}})">
      <div class="container">
        <div class="publisher-logo">
          <div class="row">
            <div class="d-inline">
              <img src="{{ asset(Auth::user()->getAvatar()) }}" alt="Logo">
            </div>
            <div class="col-lg-10 col-sm-col-xs-12">
              <div class="mt-2 info">
                <span>{{ Auth::user()->name }} {{ Auth::user()->surname }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <nav class="publisher-nav">
      <ul id="nav">
        <li><a href="{{url(\Auth::user()->slug)}}">Home</a></li>
        <li><a href="{{url(\Auth::user()->slug.'/about')}}">Informazioni</a></li>
        <li><a href="{{url(\Auth::user()->slug.'/archive')}}">Articoli Salvati</a></li>
      </ul>
    </nav>
      <div class="publisher-body">
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
            @endphp
            @if($value->type == '1') {{-- Collaborazione --}}
            @php
              $publisher_request = \DB::table('publisher_request')->find($value->content_id);
              $publisher = \DB::table('editori')->find($publisher_request->publisher_id);
            @endphp
            <div id="noty_{{ $value->id }}" class="message">
              <div class="message-content">
                <div class="message-close float-right" id="close">&times;</div>
                <div class="message-title">
                  <time>{{ $value->created_at->diffForHumans() }}</time>
                </div>
                <div class="message-body my-3 pl-3">
                  Nuova richiesta di collaborazione dalla redazione <strong>{{ $publisher->name }}</strong>
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
            @elseif($value->type == '2') {{-- Rating --}}
            @php
              $articolo = \App\Models\Articoli::where('id', $value->content_id)->first();
            @endphp
            <div id="noty_{{ $value->id }}" class="message">
              <div class="message-content">
                <div class="message-close float-right">&times;</div>
                <div class="message-title">
                  <time>{{ $value->created_at->diffForHumans() }}</time>
                </div>
                <div class="message-body">
                  <p>
                    Il tuo articolo <a href="{{ url('read/'.$articolo->slug) }}">{{ $articolo->titolo }}</a> ha ottenuto un giudizio pari a {{ $value->text }} su 5 da <a href="{{ url($value->getUserName->slug)}}">{{ $value->getUserName->name }} {{ $value->getUserName->surname }}</a>
                  </p>
                </div>
              </div>
            </div>
            @elseif($value->type == '3') {{-- Comment --}}
            @php
              $articolo = \App\Models\Articoli::where('id', $value->content_id)->first();
            @endphp
            <div id="noty_{{ $value->id }}" class="message">
              <div class="message-content">
                <div class="message-close float-right">&times;</div>
                <div class="message-title">
                  <time>{{ $value->created_at->diffForHumans() }}</time>
                </div>
                <div class="message-body">
                  <p>
                    <a href="{{ url($value->getUserName->slug)}}">{{ $value->getUserName->name }} {{ $value->getUserName->surname }}</a> ha commentato il tuo articolo <a href="{{ url('read/'.$articolo->slug) }}">{{ $articolo->titolo }}</a>
                  </p>
                </div>
              </div>
            </div>
            @endif
            <script>
            $("#noty_{{ $value->id }} .message-close").click(function(){
              App.query('get','{{ url("notification_delete") }}', { id: {{ $value->id }} }, false, function(data){
                  $("#noty_{{ $value->id }}").fadeOut();
              });
            });
            </script>
            @endforeach
          </div>
          {{ $query->links() }}
          @else
          <p>Nessuna notifica da visualizzare</p>
          @endif
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
