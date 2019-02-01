@auth
  <div class="publisher-info">
  {{--@if( $query->hasMember())
    @if($query->direttore == Auth::user()->id)
    <a href="{{ url('group/'.$query->slug.'/customize')}}">
      <button class="btn btn-primary"><i class="fas fa-cog"></i> Personalizza il sito</button>
    </a>
    @endif
  @endif--}}
    @if(!$follow)
    <button id="follow" class="btn btn-primary"><i class="fas fa-bell"></i> <span>Inizia a seguire</span></button>
    @else
    <button id="follow" class="btn btn-primary"><i class="fas fa-bell-slash"></i> <span>Smetti di seguire</span></button>
    @endif
    @if($query->hasMember())
    <li>
      <a data-toggle="dropdown" href="#">
        <button class="btn btn-primary"><i class="fas fa-ellipsis-v"></i></button>
      </a>
      <div class="dropdown-menu">
        {{--<a class="dropdown-item" href="#" data-toggle="modal" data-target="#message"><i class="fa fa-envelope"></i> Invia messaggio</a>--}}
        @if($query->direttore == Auth::user()->id)
        <a class="dropdown-item" href="{{ url('group/'.$query->slug.'/settings') }}"><i class="fa fa-cog"></i> Impostazioni</a>
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
  <script>
    //App.query('get',{{ url('') }},null,false);
  </script>
