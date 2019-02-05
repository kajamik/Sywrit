<style>
#nav > li {
  display: inline-block;
  margin-top: 5px;
}
#nav > li:not(:last-child)::after {
  content: '\00a0|';
}
._ou {
  display: inline-block;
  margin: 12px;
  cursor: pointer;
}
.publisher-info li {
  font-size: 23px;
}
</style>
@auth
  @if(!$query->accesso)
  <div class="alert">
    <h3>Questa pagina è stata disabilita e tra x giorni verrà eliminata. Per riattivarla vai sulle impostazioni della pagina.</h3>
  </div>
  @endif
  <div class="publisher-info">
    <div id="follow" class="_ou">
      @if(!$follow)
      <i class="fas fa-bell"></i> <span>Inizia a seguire</span>
      @else
      <i class="fas fa-bell-slash"></i> <span>Smetti di seguire</span>
      @endif
    </div>
    @if($query->hasMember())
    <li>
      <a data-toggle="dropdown" href="#">
        <i class="fas fa-ellipsis-v"></i>
      </a>
      <div class="dropdown-menu">
        @if($query->direttore == Auth::user()->id)
        <a class="dropdown-item" href="{{ url($query->slug.'/settings') }}"><i class="fa fa-cog"></i> Impostazioni</a>
        @endif
        <a class="dropdown-item" href="#" onclick="document.getElementById('leaveGroup').submit();"><i class="fa fa-times"></i> Abbandona il gruppo</a>
        <form id="leaveGroup" action="{{ route('group/action/leave') }}" method="POST" style="display: none;">{{ csrf_field() }}</form>
      </div>
    </li>
    @endif
  </div>
@endauth
  <div class="publisher-bar" data-pub-text="#followers">
    <i class="fa fa-newspaper" title="Articoli"></i> <span>{{$query->articoli->count()}}</span>
    <i class="fab fa-angellist" title="Follower"></i> <span id="followers">{{$query->followers_count}}</span>
  </div>
  <ul id='nav'>
    <li><a href="{{url($query->slug)}}">Home</a></li>
    <li><a href="{{url($query->slug.'/about')}}">Informazioni</a></li>
    <li><a href="{{url($query->slug.'/archive')}}">Articoli Salvati</a></li>
  </ul>
  <hr/>
