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
</style>
  <div class="publisher-home">
    <div class="publisher-header" style="background-image: url({{ $query->getBackground() }});border-radius:4px 4px 0 0;">
      <div class="container">
        <div class="publisher-logo">
          <div class="row">
            <div class="d-inline">
              <img src="{{ $query->getAvatar() }}" alt="Logo">
            </div>
            <div class="col-lg-10 col-sm-col-xs-12">
              <div class="mt-2 info">
                <span>{{ $query->name }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    @auth
    <nav class="publisher-nav">
      <ul id='nav'>
        <li class="dropdown">
            <a class="dropdown-toggle" href="#" data-toggle="dropdown">Bacheca</a>
            <div class="dropdown-menu" role="menu">
              @if(Auth::user()->groupInfo->isAdmin())
                <a class="dropdown-item" href="{{ url('groups/'. $query->id. '/admin') }}">Gestione gruppo</a>
              @endif
              @if(Auth::user()->hasMemberOf($query->id))
                <a class="dropdown-item" href="#">Abbandona gruppo</a>
              @endif
            </div>
        </li>
      </ul>
    </nav>
    @endauth
    <hr/>
    <div class="publisher-body">
      <div class="publisher-info">
