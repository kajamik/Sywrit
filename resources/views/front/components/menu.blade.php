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
            <div class="ty-search">
              <div class="d-flex">
                <input id="search_query" type="text" placeholder="Cerca" onkeyup="fetch_live_search(this.value);" />
                <div class="set d-flex">
                  <button id="search">
                    <span class="fa fa-search"></span>
                  </button>
                </div>
              </div>
              <div class="data-list"></div>
            </div>
          </li>
          <li class="dropdown">
            <a href="#" data-toggle="dropdown" role="button" aria-expanded="false" href="#">
              <span class="fa-1x fa fa-th"></span>
            </a>
            <div class="dropdown-menu" role="menu">
              @foreach($categorie as $value)
              <a class="dropdown-item" href="{{ url('topic/'.$value->slug) }}" title="Categorie">
                {{ $value->name }}
              </a>
              @endforeach
            </div>
          </li>
          @if(Auth::user())
          <li>
            <a href="{{url('write')}}">
              <i class="fa fa-newspaper" aria-hidden="true" title="Nuovo articolo"></i>
            </a>
          </li>
          <li class="dropdown">
            <a id="notification" href="#" data-toggle="dropdown" role="button" aria-expanded="false" href="#" onclick="fetch_live_notifications();" title="Notifiche">
              <i class="fa fa-bell" aria-hidden="true" title="Notifiche"></i>
            </a>
            <div class="dropdown-menu" role="menu">
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
            <a class="dropdown-toggle" href="#" data-toggle="dropdown" role="button" aria-expanded="false">
              <img class="u-icon img-circle" src="{{ asset(Auth::user()->getAvatar()) }}"><span class="user-name">{{ Auth::user()->name }}</span>
            </a>
            <div class="dropdown-menu" role="menu">
              <a class="dropdown-item" href="{{ url(Auth::user()->slug) }}"><i class="fa fa-user"></i> Il mio profilo</a>
              <a class="dropdown-item" href="{{ url('settings') }}"><i class="fa fa-cog"></i> Impostazioni</a>
              <hr/>
              @if(Auth::user()->haveGroup())
              {{-- da modificare --}}
              @foreach(Auth::user()->getPublishersInfo() as $value)
                <a class="dropdown-item" href="{{ url($value->slug) }}"><i class="fa fa-newspaper"></i> {{ $value->name }}</a>
              @endforeach
              <hr/>
              @endif
              <a class="dropdown-item" href="{{ url('publisher/create') }}"><i class="fa fa-newspaper"></i> Crea redazione</a>
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
