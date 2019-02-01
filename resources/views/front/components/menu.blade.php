@php
  $utente = Auth::user();
@endphp
<div class="navbar">
  <div class="navbar-left">
    <div class="nav-toggler">
      <span class="fa fa-bars"></span>
    </div>
    <a href="{{url('/')}}" class="brand">
      <img src="#" alt="Logo">
    </a>
    <nav class="nav">
      @auth
        @if(!$utente->haveGroup())
          <li><a href="{{url('start')}}">{{__('Crea gruppo')}}</a></li>
        @endif
      @endif
          {{--
          <li><a href="{{url('forum')}}">{{__('Forum')}}</a></li>
          <li><a href="{{url('support')}}"><i class="fa fa-life-ring"></i> {{__('Assistenza Tecnica')}}</a></li>
          --}}
          <li><a href="#">Il nostro progetto</a></li>
    </nav>
  </div>

  <div class="nav navbar-right">
        <form action="{{ route('results') }}" method="GET">
          <div class="ty-search">
            <input type="search" name="search_query" placeholder="Cerca">
            <button class="fa fa-search" style="background:transparent;border:none;"></button>
          </div>
        </form>
        <ul class="user-navbar">
          @auth
          <li>
            <a href="{{url('write')}}">
              <i class="fa fa-newspaper" aria-hidden="true" title="Nuovo articolo"></i>
            </a>
          </li>
            <li class="dropdown">
              <a data-toggle="dropdown" href="javascript:void(0)">
                <i class="fa fa-bell" aria-hidden="true" title="Notifiche"></i>
                <span class="badge badge-notification">0</span>
              </a>
              <div class="dropdown-menu">
                <div class="dropdown-header">
                  <a href="{{url('notifications')}}">
                    <i style="float:right;margin-top:5px;" class="fa fa-cog"></i>
                  </a>
                  <h5 style="display:inline-block">Notifiche (0)</h5>
                </div>
                <hr/>
                <p class="dropdown-item">Non hai notifiche</p>
              </div>
            </li>
          <li class="dropdown">
            <a class="dropdown-toggle" href="{{ url('#'. $utente->slug) }}" data-toggle="dropdown" role="button" aria-expanded="false">
              <img class="u-icon img-circle" src="{{ asset($utente->getAvatar()) }}"><span class="user-name">{{ $utente->name }}</span>
            </a>
            <div class="dropdown-menu" role="menu">
              <a class="dropdown-item" href="{{ url($utente->slug) }}"><i class="fa fa-user"></i> Il mio profilo</a>
              <a class="dropdown-item" href="{{ url('settings') }}"><i class="fa fa-cog"></i> Impostazioni</a>
              @if($utente->haveGroup())
              <a class="dropdown-item" href="{{ url('group/'.$utente->getPublisherInfo()->slug) }}"><i class="fa fa-newspaper"></i> La mia editoria</a>
              @endif
              <a class="dropdown-item" href="#adiósu" onclick="document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt"></i> Esci</a>
            </div>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">{{ csrf_field() }}</form>
          @else
            <li><a href="{{ route('login') }}">Accedi</a></li>
            <li><a href="{{ route('register') }}">Iscriviti</a></li>
         </li>
        @endauth
       </ul>
     </div>
</div>
