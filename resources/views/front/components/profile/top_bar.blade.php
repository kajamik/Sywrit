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
#customMsg {
  min-height: 200px;
}
</style>
<div class="container">
  <div class="publisher-home">
    <div class="publisher-header" style="background-image: url({{ asset($query->getBackground() )}});border-radius:4px 4px 0 0;">
      <div class="container">
        <div class="publisher-logo">
          <img src="{{ asset($query->getAvatar()) }}" alt="Logo">
        </div>
        <div class="info">
          <span>{{ $query->nome }} {{ $query->cognome }}</span>
        </div>
      </div>
    </div>
      <div class="publisher-body">
        <nav>
          <ul id='nav'>
            <li><a href="{{ url($query->slug) }}">Home</a></li>
            <li><a href="{{ url($query->slug.'/about') }}">Informazioni</a></li>
            @if(\Auth::user() && \Auth::user()->id == $query->id)
            <li><a href="{{ url($query->slug.'/archive') }}">Articoli Salvati</a></li>
            @endif
          </ul>
        </nav>
        <hr/>
        <div class="publisher-info">
          <div class="col-md-12">
            @if(!$query->id_gruppo)
            <p>Editore individuale</p>
            @else
            <p>Editore presso
              @for($i = 0; $i < count($group); $i++)
                <a class="text-underline" href="{{ url($group[$i]->slug) }}"> {{ $group[$i]->nome }}</a>
              @endfor
            </p>
            @endif
          </div>
          <div class="col-md-12">
            <span class="fa fa-newspaper"></span> {{ $count }}
          </div>
          <div class="col-md-12">
            @if($query2->sum('rating') > 0)
            <p>Media punteggio articoli: {{ round( $query2->sum('rating') / $query2->where('rating', '>', '0')->count() , 2) }} / 5</p>
            @endif
          </div>
          @if(\Auth::user() && \Auth::user()->id != $query->id)
          <div class="col-md-12">
            @if(Auth::user()->haveGroup() && Auth::user()->hasFoundedGroup())
            <div class="_ou">
              <a href="#" onclick="link('{{route('group/action/invite')}}')">
                <i class="fas fa-envelope"></i> <span>Assumi come collaboratore</span>
              </a>
            </div>
            <script>
            var array = {!! json_encode(Auth::user()->getPublishersInfo()) !!};
            var properties = [];
            Object.keys(array).forEach(i => {
              properties.push({"type": ["option"], "value": array[i].id, "text": array[i].nome});
            });

              function link(route){
                App.getUserInterface({
                  "ui": {"title": "Invito collaborazione",
                  "header": {"action": route, "method": "POST"},
                  "data": { user_id: "{{ $query->id }}", selector: "#publisherSelector", _token: "{{ csrf_token() }}" },
                  "content": [
                  {"type": ["h6"], "text": "Seleziona la redazione il quale inviare la collaborazione"},
                  {"type": [ {"select": properties} ], "class": "form-control", "name": "publisherSelector" },
                  {"type": ["button","submit"], "class": "btn btn-info", "text": "Invia Richiesta"}
                ],
              "done": function(){App.getUserInterface({"ui": {"title": "Info Redazione","content": [{"type": ["h5"], "text": "Richiesta di collaborazione inviata!!"}]}});}}});}
            </script>
            @endif
          </div>
          <div class="col-md-12">
            <div id="follow" class="_ou">
                @if(!$follow)
                <i class="fas fa-bell"></i> <span>Segui</span>
                @else
                <i class="fas fa-bell-slash"></i> <span>Smetti di seguire</span>
                @endif
            </div>
            <script>
              App.follow('#follow',{url:'{{ url("follow?q=false") }}',data:{'id':{{ $query->id }}}}, false);
              App.insl('articles');
            </script>
          </div>
        @endif
        <div class="col-md-12">
          <div class="publisher-bar" data-pub-text="#followers">
              <span id="followers">{{ $query->followers_count }}</span>
              Followers
          </div>
        </div>
      </div>
      <hr/>
