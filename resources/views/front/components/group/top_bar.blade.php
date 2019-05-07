@section('description', 'Accedi alla pagina della redazione {{ $query->name }}')

@section('seo')

    <meta property="og:title" content="{{ $query->name }} - {{ config('app.name') }}" />
    <meta property="og:description" content="Accedi alla pagina della redazione {{ $query->name }}" />
    <meta property="og:type" content="profile" />
    <meta property="og:url" content="{{ Request::url() }}" />
    <meta property="og:image" content="{{ asset($query->getAvatar()) }}" />
@endsection
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
    <div class="publisher-header" style="background-image: url({{ asset($query->getBackground()) }});border-radius:4px 4px 0 0;">
      <div class="container">
        <div class="publisher-logo">
          <div class="row">
            <div class="d-inline">
              <img src="{{ asset($query->getAvatar()) }}" alt="Logo">
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
    <nav class="publisher-nav">
      <ul id='nav'>
        <li><a href="{{url($query->slug)}}">Redazione</a></li>
        <li><a href="{{url($query->slug.'/about')}}">Contatti</a></li>
        @if(Auth::user() && $query->hasMember())
        <li><a href="{{url($query->slug.'/archive')}}">Articoli Salvati</a></li>
        <li>
          <a data-toggle="dropdown" href="#">
            Impostazioni
          </a>
          <div class="dropdown-menu">
            @if($query->direttore == Auth::user()->id && !$query->suspended)
            <a class="dropdown-item" href="{{ url($query->slug.'/settings') }}"><i class="fa fa-cog"></i> Impostazioni</a>
            @endif
            <a id="leaveGroup" class="dropdown-item" href="#" onclick="document.getElementById('leaveGroup').submit();"><i class="fa fa-times"></i> Abbandona il gruppo</a>
            <script>
            $("#leaveGroup").click(function(){
              App.getUserInterface({
              "ui": {
                "header":{"action": "{{ url('group/action/leave') }}", "method": "GET"},
                "data":{"id": "{{ $query->id }}"},
                "title": 'Info Redazione',
                "content": [
                  {"type": ["h5"], "class": "col-md-1", "label": "Vuoi lasciare questa redazione?"},
                  {"type": ["button","submit"], "name": "radio", "class": "btn btn-primary", "text": "Abbandona redazione"}
                ],
                "done": function(data){
                  if(!data.success) {
                    App.getUserInterface({
                      "ui": {
                        "title": "Info Redazione",
                        "content": [
                          {"type": ["h5"], "text": d.message}
                        ]
                      }
                    });
                  } else {
                    window.location.reload(false);
                  }
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
    <div class="publisher-body">
      <div class="publisher-info">
        @yield('group::bio')
        <div class="col-md-12">
          <span class="fa fa-newspaper"></span> {{ $query->articoli->count() }}
        </div>
        <div class="col-md-12">
          @if($query->articoli->sum('rating') > 0)
          <p>Media punteggio articoli: {{ round( $query->articoli->sum('rating') / $query->articoli->where('rating', '>', '0')->count() , 2) }} / 5</p>
          @endif
        </div>
        @if($query->suspended)
        <div class="col-md-12">
          <div class="alert alert-dark">
            <h3>Questa redazione è stata sospesa da un operatore per violazione delle <a href="{{ url('page/standards') }}" style="color:#007bff">norme della community</a>.</h3>
          </div>
        </div>
        @endif
      <hr/>
