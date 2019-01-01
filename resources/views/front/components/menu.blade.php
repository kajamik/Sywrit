<div class="navbar">
  <div class="left-navbar">
    <a class="nav-toggler">
      <span class="fa fa-bars"></span>
    </a>
    <a href="{{url('/')}}" class="brand">
      Sywrit
    </a>
    <nav class="nav">
      <ul>
        <li><a href="{{ url('publishers') }}">{{ __('Lista Editori') }}</a></li>
        @auth
        <li><a href="{{ url('publishers/start') }}">{{ __('Diventa un editore') }}</a></li>
        @endauth
      </ul>
    </nav>
  </div>

  <div class="nav right-navbar">
        <form action="{{ route('results') }}" method="GET">
          <input type="search" class="ty-search" name="search_query" placeholder="Cerca su Sywrit">
          <button class="fa fa-search" style="background:transparent;border:none;"></button>
        </form>
        </a>
        <ul class="user-navbar">
          <li class="dropdown">
            @auth
            <a class="dropdown-toggle" href="{{ url('#'. Auth::user()->slug) }}" data-toggle="dropdown" role="button" aria-expanded="false">
              <img class="u-icon img-circle" src="{{ asset(Auth::user()->getAvatar()) }}"><span class="user-name">{{ Auth::user()->name }}</span>
            </a>
            <div class="dropdown-menu" role="menu">
              <div class="user-info">
                <div class="user-name">{{ Auth::user()->name }}</div>
                <div class="user-badge online"> Disponibile</div>
              </div>
              <a class="dropdown-item" href="{{ url('impostazioni') }}"><i class="fa fa-cog"></i> Impostazioni</a>
              <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();"><i class="fa fa-sign-out"></i> Scollegati</a>
            </div>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">{{ csrf_field() }}</form>
          @else
          {{--<a class="dropdown-toggle" href="{{ url('#') }}" data-toggle="dropdown" role="button" aria-expanded="false">
            <img class="u-icon img-circle" src="{{ asset('upload/default.png') }}">
          </a>
          <div class="dropdown-menu" role="menu">
            <a class="dropdown-item" href="{{ route('register') }}">{{ __('Crea un nuovo account') }}</a>
            <a class="dropdown-item" href="{{ route('login') }}">{{ __('Accedi') }}</a>
          </div>
          --}}
          <li><a href="{{ route('login') }}">Accedi</a></li>
          @endauth
         </li>
       </ul>
      </div>
</div>
