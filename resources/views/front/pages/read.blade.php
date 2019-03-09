@extends('front.layout.app')

@section('title', $query->titolo.' -')

@php
  $autore = \App\Models\User::find($query->autore);
  if($query->id_gruppo > 0)
    $editore = \App\Models\Editori::find($query->id_gruppo);

  $collection = collect(explode(',',$autore->followers));
  if(Auth::user() && $collection->some(\Auth::user()->id)){
    $follow = true;
  }else{
    $follow = false;
  }
@endphp

@section('main')
<style>
.publisher-body .block-article a, .publisher-body .block-article a:hover {
  text-decoration: underline;
}
div.date-info {
  margin-top: 12px;
}
span.date {
  text-transform: capitalize;
}
span.time {
  padding: 0;
}
.block-body {
  padding: 12px;
}
.block-footer > .feeds {
  margin-top: 15px;
  font-size: 33px;
}
.btn-custom {
  background-color: #fff;
  border: 1px solid #000;
  border-radius: 3px;
}
.btn-custom:active {
  outline: none;
}
._button_active_ {
  background-color: #A22932;
  color: #fff;
}
</style>
<div class="container">
  <div class="publisher-home">
    @if(!empty($editore))
    <section class="publisher-header" style="background-image: url({{asset($editore->getBackground())}})">
      <div class="container">
        <img class="publisher-logo" src="{{asset($editore->getLogo())}}" alt="Logo">
        <div class="info">
          <span>{{$editore->nome}}</span>
        </div>
      </div>
    </section>
    @else
    <section class="publisher-header" style="background-image: url({{asset($autore->getBackground())}})">
      <div class="container">
        <img class="publisher-logo" src="{{asset($autore->getAvatar())}}" alt="Logo">
        <div class="info">
          <span>{{$autore->nome}} {{$autore->cognome}}</span>
        </div>
      </div>
    </section>
    @endif
    <section class="publisher-body">
      @if($options)

        @if($query->id_gruppo > 0)

          @include('front.components.article.group_tools')

        @else
          @include('front.components.article.my_tools')
        @endif

        @if(!$query->status)
          Articolo non pubblicato
        @endif

      @endif
      <article class="block-article">
        <div class="block-title">
          <h1>{{ $query->titolo }}</h1>
        </div>
        <div>
          Scritto da <a href="{{ url($autore->slug) }}">{{ $autore->nome }} {{ $autore->cognome }}</a>
          @if(Auth::user() && $query->autore != \Auth::user()->id)
          <button id="follow" class="btn-custom">
            <i id="follow_icon" class="@if($follow) fas @else far @endif fa-bell"></i> <span>{{ $autore->followers_count }} seguaci</span>
          </button>
          <script>
          document.getElementById("follow").onclick = function(){
            App.query('GET','{{ route("follow") }}', {id: '{{ $query->id }}',_token: '{{ csrf_token() }}'}, false, function(data){
              if(data.result){
                $("#follow_icon").attr("class","fa fa-bell");
              }else{
                $("#follow_icon").attr("class","far fa-bell");
              }
              $("#follow span").text(data.counter+" Seguaci");
            });
          }
          </script>
          @endif
          <div class="date-info">
            <span class="date"><i class="far fa-calendar-alt"></i> {{ $date }}</span>
            <span class="time"><i class="far fa-clock"></i> {{ $time }}</span><br/>
          </div>
        </div>
        <hr/>
        <div class="block-body">
          {{--<img src="{{ asset($query->getBackground()) }}" style="max-height:230px;" alt="copertina"> --}}
          <p>
            {!! $query->testo !!}
          </p>
        </div>
        @if(!empty($query->tags))
        <div class="block-meta">
          <ul class="meta-tags">
            <i class="fa fa-tags"></i>
            @foreach($tags as $tag)
              <li><a href="{{ url('search/tag/'.$tag) }}">#{{$tag}}</a></li>
            @endforeach
          </ul>
        </div>
        @endif
      <hr/>
      <div class="block-footer">
        <div class="socials">
          <button id="like" class="btn-custom @if($like) _button_active_ @endif">
            {{ $query->likes_count }} Mi piace
          </button>
          <p><a id="share" href="#share">Condividi</a></p>
          @auth<p><a id="report" href="#report">Segnala</a></p>@endauth
        </div>
        <div class="feeds">
          <!-- Da fare -->
        </div>
    </article>
  </section>
  @auth
  <script>
    document.getElementById("like").onclick = function(){
      App.query('GET','{{ url("like") }}', {id: '{{ $query->id }}'}, false, function(data){
        if($("#like").hasClass("_button_active_")){
          $("#like").removeClass("_button_active_");
        }else{
          $("#like").addClass("_button_active_");
        }
          $("#like").text(data.counter+" Mi piace");
      });
    }
    document.getElementById("report").onclick = function(){
      App.getUserInterface({
      "ui": {
        "header":{"action": "{{route('article/action/report')}}", "method": "POST"},
        "data":{"id": "{{$query->id}}", "_token": "{{ csrf_token() }}", "selector": "#selOption:checked", "text": "#reasonText"},
        "title": 'Segnala articolo',
        "content": [
          {"type": ["input","radio"], "id":"selOption", "name": "option", "value": "0", "class": "col-md-1", "label": "Contenuto di natura sessuale", "required": true},
          {"type": ["input","radio"], "id":"selOption", "name": "option", "value": "1", "class": "col-md-1", "label": "Contenuti violenti o che incitano all\'odio", "required": true},
          {"type": ["input","radio"], "id":"selOption", "name": "option", "value": "2", "class": "col-md-1", "label": "Promuove il terrorismo o attività criminali", "required": true},
          {"type": ["input","radio"], "id":"selOption", "name": "option", "value": "3", "class": "col-md-1", "label": "Violazione del diritto d\'autore", "required": true},
          {"type": ["textarea"], "id":"reasonText", "name": "reason", "value": "", "class": "form-control", "placeholder": "Motiva la segnalazione (opzionale)"},
          {"type": ["button","submit"], "name": "radio", "class": "btn btn-danger", "text": "invia segnalazione"}
        ],
        "done": function(){
          App.getUserInterface({
            "ui": {
              "title": "Segnalazione",
              "content": [
                {"type": ["h5"], "text": "Grazie per la segnalazione."}
              ]
            }
          });
        }

      } // -- End Interface --
    });
  }
  </script>
  @else
  <script>
  document.getElementById("like").onclick = function(){guest();};
  function guest(){
    App.getUserInterface({
      "ui": {
        "title": "Ops...",
        "content": [
          {"type": ["h5"], "text": "<a href='/login'>Accedi</a> o <a href='/register'>registrati</a> per eseguire questa azione"}
        ]
      }
    });
  }
  </script>
  @endauth
  <script>
    document.getElementById("share").onclick = function(){
      App.getUserInterface({
      "ui": {
        "title": "Condividi su",
        "content": [
          {"type": ["a"], "href": "https://www.facebook.com/share.php?u={{Request::url()}}", "text":"<i class='fab fa-facebook-square share-url'></i>"},
          {"type": ["a"], "href": "https://twitter.com/intent/tweet?url={{Request::url()}}", "text": "<i class='fab fa-twitter-square share-url'></i>"},
          {"type": ["a"], "href": "https://www.linkedin.com/sharing/share-offsite/?url={{Request::url()}}", "text": "<i class='fab fa-linkedin share-url'></i>"}
        ],
      }
    });
  }

  </script>
  </div>
</div>
@endsection
