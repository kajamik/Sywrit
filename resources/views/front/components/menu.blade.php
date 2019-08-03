@php
  $categorie = \App\Models\ArticleCategory::orderBy('name','asc')->get();
@endphp
<div class="navbar">
    <a href="{{url('/')}}" class="brand">
      <img src="{{ asset('upload/46x46/black_logo.png') }}" alt="Logo">
    </a>

  <div class="nav">
        <ul class="user-navbar container">
          <li>
            <form action="{{ url('search/') }}" method="get">
              <div class="ty-search">
                <div class="d-flex">
                  <input id="search_query" name="q" type="text" placeholder="Cerca" onkeyup="fetch_live_search(this.value);" />
                  <div class="set d-flex">
                    <button id="search" type="submit" role="button" aria-label="true">
                      <span class="fa fa-search"></span>
                    </button>
                  </div>
                </div>
                <div class="data-list"></div>
              </div>
            </form>
            <script>
            $("form").submit(function(){
              if($.trim($("form input[name=q]").val()).length > 0) {
                window.location = "{{ url('search') }}/" + $("form input[name=q]").val();
              }
              return false;
            });
            </script>
          </li>
          <li>
            <a id="support" href="#support">
              <i class="fa fa-question-circle" aria-hidden="true" title="Contatta il supporto"></i>
            </a>
          </li>
          @auth
          <script>
          $("#support").click(function(){
            App.getUserInterface({
            "ui": {
              "header":{"action": "{{ url('action/support') }}", "method": "GET"},
              "data":{"text": "#text", "selector": "#selector"},
              "title": 'Contatta il supporto',
              "content": [
                {"type": [{"select": [{"type": ["option"], "value": "1", "text": "Supporto"},{"type": ["option"], "value": "2", "text": "Feedback"},]}], "id": "selector", "name": "selector", "class": "form-control"},
                {"type": ["textarea"], "id":"text", "name": "message", "value": "", "class": "form-control", "placeholder": "Scrivi un messaggio", "required": true },
                {"type": ["button","submit"], "id": "message", "class": "btn btn-info", "text": "Invia messaggio"}
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
          @else
          <script>
          $("#support").click(function(){
            App.getUserInterface({
            "ui": {
              "header":{"action": "{{ url('action/support') }}", "method": "GET"},
              "data":{ "text": "#text", "email": "#email", "selector": "#selector"},
              "title": 'Contatta il supporto',
              "content": [
                {"type": ["input","email"], "id": "email", "name": "email", "class": "form-control", "placeholder": "Indirizzo e-mail", "required": true},
                {"type": [{"select": [{"type": ["option"], "value": "1", "text": "Supporto"},{"type": ["option"], "value": "2", "text": "Feedback"},]}], "id": "selector", "name": "selector", "class": "form-control"},
                {"type": ["textarea"], "id": "text", "name": "message", "value": "", "class": "form-control", "placeholder": "Scrivi un messaggio", "required": true },
                {"type": ["button","submit"], "id": "message", "class": "btn btn-info", "text": "Invia messaggio"}
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
          @auth
          <li>
            <a href="{{url('write')}}">
              <i class="fa fa-newspaper" aria-hidden="true" title="Nuovo articolo"></i>
            </a>
          </li>
          @endauth
          <li class="dropdown">
            <a href="#" data-toggle="dropdown" role="button" aria-expanded="false" title="Categorie">
              <span class="fa-1x fa fa-th"></span>
            </a>
            <div class="dropdown-menu ml-5">
              @foreach($categorie as $value)
              <a class="dropdown-item" href="{{ url('topic/'.$value->slug) }}">
                {{ $value->name }}
              </a>
              @endforeach
            </div>
          </li>
          @auth
          <li class="dropdown">
          <a id="notification" href="#" data-toggle="dropdown" onclick="fetch_live_notifications();" title="Notifiche">
            <i class="fa fa-bell" aria-hidden="true" title="Notifiche"></i>
            <span class='badge badge-notification'></span>
          </a>
          <div class="dropdown-menu">
            <div class="notification-header">
              <div class="notification-title">
                <h3>Notifiche</h3>
              </div>
              <div class="notification-opts">
                <a href="{{ url('notifications') }}">
                  <span class="fa fa-cogs"></span>
                </a>
              </div>
            </div>
            <div class="notification-content">
              <div class="data-notification">
              </div>
            </div>
          </div>
        </li>
        <li class="dropdown">
            <a class="dropdown-toggle" href="#" data-toggle="dropdown">
              <img class="u-icon img-circle" src="{{ Auth::user()->getAvatar() }}" alt="dropdown"><span class="user-name">{{ Auth::user()->name }}</span>
            </a>
            <div class="dropdown-menu" role="menu">
              <a class="dropdown-item" href="{{ url(Auth::user()->slug) }}"><i class="fa fa-user"></i> Il mio profilo</a>
              <a class="dropdown-item" href="{{ url(Auth::user()->slug.'/archive') }}"><i class="fa fa-file-archive"></i> Articoli Salvati</a>
              <a class="dropdown-item" href="{{ url('settings') }}"><i class="fa fa-cog"></i> Impostazioni</a>
              <hr/>
              {{--
              @if(Auth::user()->haveGroup())
              @php
                $gruppi = Auth::user()->getPublishersInfo();
              @endphp

              @foreach($gruppi as $value)
                <a class="dropdown-item" href="{{ url($value->slug) }}"><i class="fa fa-newspaper"></i> {{ $value->name }}</a>
              @endforeach

              <hr/>
              @endif
              <a class="dropdown-item" href="{{ url('publisher/create') }}"><i class="fa fa-newspaper"></i> Crea redazione</a>
              --}}
              @if(Auth::user()->isOperator())
              <a class="dropdown-item" href="{{ url('toolbox')}}" target="_blank"><i class="fa fa-toolbox"></i> Strumenti</a>
              @endif
              <a class="dropdown-item" href="#adiósu" onclick="document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt"></i> Esci</a>
            </div>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">{{ csrf_field() }}</form>
          @else
            <li><a href="{{ route('login') }}">Accedi</a></li>
            <li><a href="{{ route('register') }}">Iscriviti</a></li>
          @endauth
      </li>
    </ul>
  </div>
</div>
