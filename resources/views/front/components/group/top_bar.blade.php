@auth
  <div class="publisher-info">
  @if( $query->hasMember())
    @if($query->direttore == Auth::user()->id)
    <a href="{{ url('group/'.$query->slug.'/customize')}}">
      <button class="btn btn-primary"><i class="fas fa-cog"></i> Personalizza il sito</button>
    </a>
    @endif
  @else
    @if(!$follow)
    <button id="follow" class="btn btn-primary"><i class="fas fa-bell"></i> <span>Inizia a seguire</span></button>
    @else
    <button id="follow" class="btn btn-primary"><i class="fas fa-bell-slash"></i> <span>Smetti di seguire</span></button>
    @endif
  @endif
  </div>
@endauth
  <div class="publisher-bar" data-pub-text="#followers">
    <i class="fa fa-newspaper" title="Articoli"></i> <span>{{$query->articoli->count()}}</span>
    <i class="fab fa-angellist" title="Follower"></i> <span id="followers">{{count($followers)}}</span>
    <ol>
      <a href="{{ url('group/'.$query->slug)}}">
        <i class="fas fa-home"></i> Home Page
      </a>
      <a href="{{ url('group/'.$query->slug.'/articles')}}">
        <i class="fas fa-newspaper"></i> Articoli
      </a>
      <a href="{{ url('group/'.$query->slug.'/contact')}}">
        <i class="fas fa-phone"></i> Contatti
      </a>
    </ol>
  </div>
