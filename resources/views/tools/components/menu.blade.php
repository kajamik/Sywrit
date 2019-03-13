<div class="navbar">
  <div class="navbar-left">
    <a href="{{url('/toolbox')}}" class="brand">
      <img src="{{ asset('upload/logo_white.png') }}" alt="Logo">
    </a>
    <nav class="nav">
      <li><a href="{{url('toolbox/users')}}">Utenti</a></li>
      <li><a href="{{url('toolbox/pages')}}">Pagine</a></li>
      <li><a href="{{url('toolbox/reports_activity')}}">Segnalazioni</a></li>
    </nav>
  </div>
  {{--<div class="nav navbar-right">
    <ul class="user-navbar">
      <li>Codice utente: {{ Auth::user()->id }}</li>
    </ul>
  </div>--}}
</div>
