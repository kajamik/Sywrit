<style>
#nav > li {
  display: inline-block;
  margin-top: 5px;
}
#nav > li:not(:last-child)::after {
  content: '\00a0|';
}
._ou {
  cursor: pointer;
}
#customMsg {
  min-height: 200px;
}
.publisher-bar > ul {
  display: inline-block;
}
</style>
  <div class="publisher-home">
    <section class="publisher-header" style="background-image: url({{asset($query->getBackground())}})">
      <div class="container">
        <img class="publisher-logo" src="{{asset($query->getAvatar())}}" alt="Logo">
        <div class="info">
          <span>{{$query->nome}} {{$query->cognome}}</span>
        </div>
      </div>
    </section>
    <section class="publisher-body">
      <div class="container">
        @if($query->id_gruppo == 0)
          <p>Editore individuale</p>
        @else
          <p>Editore presso <a href="{{ url($query->getPublisherInfo()->slug) }}">{{$query->getPublisherInfo()->nome}}</a></p>
        @endif
        @if(\Auth::user() && \Auth::user()->id != $query->id)
        <div class="publisher-info">
          @if(Auth::user()->haveGroup() && Auth::user()->isDirector() && !$query->haveGroup())
          <div class="_ou">
            <a href="#" onclick="link('{{route('group/action/invite')}}')">
              <i class="fas fa-envelope"></i> <span>Assumi come collaboratore</span>
            </a>
          </div>
          <script>
          function link(route){
              App.getUserInterface({
                "ui": {
                  "title": "Invito collaborazione",
                  "header": {"action": route,"method": "POST"},
                  "data": { id: "{{$query->id}}", _token: " {{ csrf_token() }}", text: "#customMsg" },
                  "content": [
                    {"type": ["h5"], "text": "Crea un messaggio personalizzato"},
                    {"type": ["textarea"], "id": "customMsg", "class": "form-control", "text": "Gentile utente,\r\n\r\nSei invitato a collaborare con {{ Auth::user()->getPublisherInfo()->nome }}.\r\n\r\nCordiali Saluti, {{ Auth::user()->nome }} {{ Auth::user()->cognome }}"},
                    {"type": ["button","submit"], "class": "btn btn-info", "text": "Invia messaggio"}
                  ],
                  "done": function(){
                    App.getUserInterface({
                      "ui": {
                        "title": "Info Redazione",
                        "content": [
                          {"type": ["h5"], "text": "Richiesta di collaborazione inviata!!"}
                        ]
                      }
                });
              }

            }
            });
          }
        </script>
          @endif
          <div id="follow" class="_ou">
            @if(!$follow)
              <i class="fas fa-bell"></i> <span>Segui</span>
            @else
              <i class="fas fa-bell-slash"></i> <span>Smetti di seguire</span>
            @endif
          </div>
          <script>
              App.follow('#follow',
              {
                url:'{{url("follow")}}',
                data:{'id': {{ $query->id }}, 'mode': 'i'}
                }, false);
              App.insl('articles');
            </script>
        </div>
        @endif
        <div class="publisher-bar" data-pub-text="#followers">
          <div>
              <span id="followers">{{$query->followers_count}}</span>
              Seguaci
          </div>
        </div>
        <ul id='nav'>
          <li><a href="{{ url($query->slug) }}">Home</a></li>
          <li><a href="{{ url($query->slug.'/about') }}">Informazioni</a></li>
        @if(\Auth::user() && \Auth::user()->id == $query->id)
          <li><a href="{{ url($query->slug.'/archive') }}">Articoli Salvati</a></li>
        @endif
        </ul>
        <hr/>
