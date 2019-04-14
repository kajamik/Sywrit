@extends('front.layout.app')

@section('title', 'Contatti - '.$query->nome.' '.$query->cognome.' -')

@section('main')
<style>
#nav > li {
  display: inline-block;
  margin-top: 5px;
  margin-bottom: 10px;
  font-size: 20px;
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
</style>
<div class="container">
  <div class="publisher-home">
    <div class="publisher-header" style="background-image: url({{ asset($query->getBackground() )}});border-radius:4px 4px 0 0;">
      <div class="container">
        <div class="publisher-logo d-flex">
          <img src="{{ asset($query->getAvatar()) }}" alt="Logo">
          <div class="ml-4 mt-3 info">
            <span class="verified">{{ $query->nome }} {{ $query->cognome }}</span>
          </div>
        </div>
      </div>
    </div>
      <div class="publisher-body">
        {{--<div class="">
          <div class="publisher-logo">
            <img src="{{ asset($query->getAvatar()) }}" alt="Logo">
          </div>
        </div>--}}
        <nav>
          <ul id='nav'>
            <li><a href="{{ url($query->slug) }}">Profilo</a></li>
            <li><a href="{{ url($query->slug.'/about') }}">Contatti</a></li>
            @if(\Auth::user() && \Auth::user()->id == $query->id)
            <li><a href="{{ url($query->slug.'/archive') }}">Articoli Salvati</a></li>
            @endif
          </ul>
        </nav>
        <hr/>
        <div class="publisher-info">
          @if(\Auth::user() && \Auth::user()->id != $query->id)
          <div class="col-md-12">
            @if(Auth::user()->haveGroup() && Auth::user()->hasFoundedGroup())
            <div class="_ou">
              <a href="#" onclick="link('{{route('group/action/invite')}}')">
                <i class="fas fa-envelope"></i> <span>Assumi come collaboratore</span>
              </a>
            </div>
            <script>
            var array = {!! json_encode(Auth::user()->getPublishersInfo()) !!};
            var properties = [];
            Object.keys(array).forEach(i => {
              properties.push({"type": ["option"], "value": array[i].id, "text": array[i].nome});
            });

              function link(route){
                App.getUserInterface({
                  "ui": {"title": "Invito collaborazione",
                  "header": {"action": route, "method": "POST"},
                  "data": { user_id: "{{ $query->id }}", selector: "#publisherSelector", _token: "{{ csrf_token() }}" },
                  "content": [
                  {"type": ["h6"], "text": "Seleziona la redazione il quale inviare la collaborazione"},
                  {"type": [ {"select": properties} ], "class": "form-control", "name": "publisherSelector" },
                  {"type": ["button","submit"], "class": "btn btn-info", "text": "Invia Richiesta"}
                ],
              "done": function(d){App.getUserInterface({"ui": {"title": "Info Redazione","content": [{"type": ["h5"], "text": d.message}]}});}}});}
            </script>
            @endif
          </div>
          {{--<div class="col-md-12">
            <div id="follow" class="_ou">
                @if(!$follow)
                <i class="fas fa-bell"></i> <span>Segui</span>
                @else
                <i class="fas fa-bell-slash"></i> <span>Smetti di seguire</span>
                @endif
            </div>
            <script>
              App.follow('#follow',{url:'{{ url("follow?q=false") }}',data:{'id':{{ $query->id }}}}, false);
              App.insl('articles');
            </script>
          </div>--}}
        @endif
        {{--<div class="col-md-12">
          <div class="publisher-bar" data-pub-text="#followers">
              <span id="followers">{{ $query->followers_count }}</span>
              Followers
          </div>
        </div>--}}
        @auth
        <div class="col-md-12">
          <a id="report" href="#report">
            Segnala utente
          </a>
        </div>
        <script>
          $("#report").click(function(){
            App.getUserInterface({
            "ui": {
              "header":{"action": "{{route('article/action/report')}}", "method": "GET"},
              "data":{"id": "{{$query->id}}", "selector": "#selOption:checked", "text": "#reasonText"},
              "title": 'Segnala utente',
              "content": [
                {"type": ["input","radio"], "id": "selOption", "name": "option", "value": "0", "class": "col-md-1", "label": "Contenuto di natura sessuale", "required": true},
                {"type": ["input","radio"], "id": "selOption", "name": "option", "value": "1", "class": "col-md-1", "label": "Contenuti violenti o che incitano all\'odio", "required": true},
                {"type": ["input","radio"], "id": "selOption", "name": "option", "value": "2", "class": "col-md-1", "label": "Promuove il terrorismo o attività criminali", "required": true},
                {"type": ["input","radio"], "id": "selOption", "name": "option", "value": "3", "class": "col-md-1", "label": "Violazione del diritto d\'autore", "required": true},
                {"type": ["textarea"], "id":"reasonText", "name": "reason", "value": "", "class": "form-control", "placeholder": "Motiva la segnalazione (opzionale)"},
                {"type": ["button","submit"], "name": "radio", "class": "btn btn-danger", "text": "invia segnalazione"}
              ],
              "done": function(){
                App.getUserInterface({
                  "ui": {
                    "title": "Segnalazione",
                    "content": [
                      {"type": ["h5"], "text": "Grazie per la segnalazione."}
                    ]
                  }
                });
              }

            } // -- End Interface --
          });
        });
        </script>
        @endauth
      </div>
      <hr/>

<style>
h2 {
  padding: 8px
  border-radius: 4px;
  text-transform: uppercase;
  font-size: 23px;
}
h2 + div {
  padding: 15px;
}
address > a, address > a:hover {
  text-decoration: underline;
}
</style>

        <div class="publisher-content">
          <div class="py-3">
            @if(!empty($query->biography))
            <h2>Biografia</h2>
            <div class="col-lg-12">
              <p>{!! $query->biography !!}</p>
            </div>
            <hr/>
            @endif
            {{--
            <div class="col-12">
              <h2>Followers</h2>
              <div class="col-lg-12">
                <div class="row">
                  @foreach($followers as $value)
                  @php
                    $user = \App\Models\User::where('id',$value)->first();
                  @endphp
                  <div class="v_card col-lg-2 col-sm-8 col-xs-12">
                    <a href="{{ url($user->slug) }}">
                      <div class="card">
                        <img class="card-img-top" src="{{ asset($user->getAvatar()) }}" alt="Avatar">
                        <div class="card-body">
                          <strong class="card-title">{{ $user->nome }} {{ $user->cognome }}</strong>
                        </div>
                      </div>
                    </a>
                  </div>
                  @endforeach
                </div>
              </div>
            </div>
            --}}
            @if(!empty($query->facebook) || !empty($query->instagram) || !empty($query->linkedin))
            <div class="col-12">
            <h2>Socials Link</h2>
            <div class"col-lg-12">
              @if(!empty($query->facebook))
              <address>
                Facebook: <a href="https://facebook.com/{{ $query->facebook }}" target="_blank">{{ $query->facebook }}</a>
              </address>
              @endif
              @if(!empty($query->instagram))
              <address>
                Instagram: <a href="https://instagram.com/{{ $query->instagram }}" target="_blank">{{ $query->instagram }}</a>
              </address>
              @endif
              @if(!empty($query->linkedin))
              <address>
                Linkedin: <a href="https://linkedin.com/in/{{ $query->linkedin }}" target="_blank">{{ $query->linkedin }}</a>
              </address>
              @endif
            </div>
            </div>
            @else
              <p>Nessun contatto</p>
            @endif
          </div>
    </div>
  </div>
</section>
</div>
@endsection
