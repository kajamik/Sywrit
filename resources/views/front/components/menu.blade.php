<div class="navbar">
  <div class="navbar-left">
    <a href="{{url('/')}}" class="brand">
      <img src="{{ asset('upload/logo_white.png') }}" alt="Logo">
    </a>
  </div>

  <div class="nav navbar-right">
        <ul class="user-navbar">
          <li>
            <div class="ty-search" id="search">
              <input id="search_query" type="text" placeholder="Cerca" onkeyup="fetch_live_search(this.value);" />
              <button style="background:transparent;border:none;">
                <span class="fa fa-search"></span>
              </button>
              <div class="data-list"></div>
            </div>
          </li>
          @if(Auth::user())
          <li>
            <a href="{{url('write')}}">
              <i class="fa fa-newspaper" aria-hidden="true" title="Nuovo articolo"></i>
            </a>
          </li>
          <li class="dropdown">
            <a href="#" data-toggle="dropdown" role="button" aria-expanded="false" href="#" onclick="fetch_live_notifications();">
              <i class="fa fa-bell" aria-hidden="true" title="Notifiche"></i>
              @if(\Auth::user()->notifications_count)
              <span class="badge badge-notification">{{ \Auth::user()->notifications_count }}</span>
              @endif
            </a>
            <div class="dropdown-menu" role="menu">
              <div class="notification-header">
                <div class="notification-title">
                  <h3>Notifiche</h3>
                </div>
                <div class="notification-opts">
                  <a href="{{ url('notifications') }}">
                    <span class="fa fa-bars"></span>
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
            <a class="dropdown-toggle" href="#" data-toggle="dropdown" role="button" aria-expanded="false">
              <img class="u-icon img-circle" src="{{ asset(Auth::user()->getAvatar()) }}"><span class="user-name">{{ Auth::user()->name }}</span>
            </a>
            <div class="dropdown-menu" role="menu">
              <a class="dropdown-item" href="{{ url(Auth::user()->slug) }}"><i class="fa fa-user"></i> Il mio profilo</a>
              <a class="dropdown-item" href="{{ url('settings') }}"><i class="fa fa-cog"></i> Impostazioni</a>
              @if(Auth::user()->haveGroup())
              <a class="dropdown-item" href="{{ url(Auth::user()->getPublisherInfo()->slug) }}"><i class="fa fa-newspaper"></i> La mia pagina</a>
              @else
              <a class="dropdown-item" href="{{ url('publisher/create') }}"><i class="fa fa-newspaper"></i> Crea gruppo</a>
              @endif
              @if(Auth::user()->isOperator())
              <a class="dropdown-item" href="{{ url('toolbox')}}" target="_blank"><i class="fa fa-toolbox"></i> Strumenti</a>
              @endif
              <a class="dropdown-item" href="#adiósu" onclick="document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt"></i> Esci</a>
            </div>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">{{ csrf_field() }}</form>
          @else
            <li><a href="{{ route('login') }}">Accedi</a></li>
            <li><a href="{{ route('register') }}">Iscriviti</a></li>
        @endif
        </li>
       </ul>
     </div>
</div>
