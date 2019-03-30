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
@auth
<div class="container">
  <div class="publisher-home">
    <div class="publisher-header" style="background-image: url({{asset($query->getBackground())}});border-radius:4px 4px 0 0;">
      <div class="container">
        <div class="publisher-logo">
          <img src="{{asset($query->getLogo())}}" alt="Logo">
        </div>
        <div class="info">
          <span>{{$query->nome}}</span>
        </div>
      </div>
    </div>
    <div class="publisher-body">
      <nav>
        <ul id='nav'>
          <li><a href="{{url($query->slug)}}">Home</a></li>
          <li><a href="{{url($query->slug.'/about')}}">Informazioni</a></li>
          <li><a href="{{url($query->slug.'/archive')}}">Articoli Salvati</a></li>

          @if($query->hasMember())
          <li>
            <a data-toggle="dropdown" href="#">
              Impostazioni
            </a>
            <div class="dropdown-menu">
              @if($query->direttore == Auth::user()->id)
              <a class="dropdown-item" href="{{ url($query->slug.'/settings') }}"><i class="fa fa-cog"></i> Impostazioni</a>
              @endif
              <a id="leaveGroup" class="dropdown-item" href="#" onclick="document.getElementById('leaveGroup').submit();"><i class="fa fa-times"></i> Abbandona il gruppo</a>
              <script>
              $("#leaveGroup").click(function(){
                App.getUserInterface({
                "ui": {
                  "header":{"action": "{{route('group/action/leave')}}", "method": "POST"},
                  "data":{"id": "{{$query->id}}", "_token": "{{ csrf_token() }}"},
                  "title": 'Abbandono redazione',
                  "content": [
                    {"type": ["h5"], "class": "col-md-1", "label": "Vuoi davvero lasciare questa redazione?"},
                    {"type": ["button","submit"], "name": "radio", "class": "btn btn-primary", "text": "Abbandona redazione"}
                  ],
                  "done": function(){
                    App.getUserInterface({
                      "ui": {
                        "title": "Abbandono redazione",
                        "content": [
                          {"type": ["h5"], "text": "Hai abbandonato la redazione"}
                        ]
                      }
                    });
                  }

                } // -- End Interface --
              });
              });
              </script>
            </div>
          </li>
          @endif
        </ul>
      </nav>
      <hr/>

        @if(\Session::get('alert'))
        <script>
          new Noty({
            theme: "sunset",
            text: "{{ Session::get('alert') }}",
          }).show();
        </script>
        @endif

  <div class="publisher-info">
    <div class="col-md-12">
    @if(!$query->accesso)
      <div class="alert alert-info">
        <h3>Questa pagina è stata disabilita. Per riattivarla vai sulle impostazioni della pagina.</h3>
      </div>
    @endif
      <div id="follow" class="_ou">
      @if(!$follow)
      <i class="fas fa-bell"></i> <span>Inizia a seguire</span>
      @else
      <i class="fas fa-bell-slash"></i> <span>Smetti di seguire</span>
      @endif
      </div>
    </div>
    @endauth
    <div class="col-md-12">
      <div class="publisher-bar" data-pub-text="#followers">
        <div>
          <span id="followers">{{ $query->followers_count }}</span>
          Followers
        </div>
      </div>
    </div>
  </div>
  <hr/>
