@extends('front.layout.app')

@section('title', $query->titolo. ' -')

@php
  $autore = \App\Models\User::find($query->id_autore);
  if($query->id_gruppo > 0) {
    $editore = \App\Models\Editori::find($query->id_gruppo);
    //$collection = collect(explode(',',$editore->followers));
  } else {
    //$collection = collect(explode(',',$autore->followers));
  }

  /*if(Auth::user() && $collection->some(\Auth::user()->id)){
    $follow = true;
  }else{
    $follow = false;
  }*/

@endphp

@section('seo')

    <meta property="og:title" content="{!! $query->titolo !!} - {{ config('app.name') }}" />
    <meta property="og:description" content="{!! str_limit(strip_tags($query->testo), 40, '...') !!}" />
    <meta property="og:type" content="article" />
    <meta property="og:url" content="{{ Request::url() }}" />
    <meta property="og:image" content="{{ asset($query->getBackground()) }}" />
    <meta property="article:published_time" content="{{ $query->created_at }}" />
    <meta property="article:author" content="{{ $autore->nome }} {{ $autore->cognome }}" />
    <meta property="article:tag" content="{{ $query->tags }}" />
@endsection

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
.feeds {
  padding: 15px;
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
    {{--@if(!empty($editore))
    <div class="publisher-header" style="background-image: url({{ asset($editore->getBackground()) }})">
      <div class="container">
        <div class="publisher-logo">
          <img src="{{ asset($editore->getLogo()) }}" alt="Logo">
        </div>
        <div class="info">
          <span>{{ $editore->nome }}</span>
        </div>
      </div>
    </div>
    @else
    <div class="publisher-header" style="background-image: url({{ asset($autore->getBackground()) }})">
      <div class="container">
        <div class="publisher-logo">
          <img src="{{asset($autore->getAvatar())}}" alt="Logo">
        </div>
        <div class="info">
          <span>{{ $autore->nome }} {{ $autore->cognome }}</span>
        </div>
      </div>
    </div>
    @endif--}}
    <div class="publisher-body">
        @auth
        <div class="publisher-info">
          @if($query->id_gruppo > 0)
            @if($query->id_autore == \Auth::user()->id)
              @include('front.components.article.group_tools')
            @endif
          @else
            @if($query->id_autore == \Auth::user()->id)
              @include('front.components.article.my_tools')
            @endif
          @endif
          {{--@if(Auth::user() && $query->id_autore != \Auth::user()->id)
          <div class="col-md-12">
            <button id="follow" class="btn-custom">
              @if($follow)
              <span id="follow_icon" class="fas fa-bell"></span>
              <strong id="follow_text">{{ trans('Smetti di seguire') }}</strong>
              @else
              <span id="follow_icon" class="far fa-bell"></span>
              <strong id="follow_text">{{ trans('Inizia a seguire') }}</strong>
              @endif
            </button>
          </div>
          <script>
          document.getElementById("follow").onclick = function(){
            App.query('GET','{{ url("follow?q=true") }}', {id: '{{ $query->id_gruppo }}'}, false, function(data){
              if(data.result){
                $("#follow_icon").attr("class","fa fa-bell");
                $("#follow strong").text("Smetti di seguire");
              }else{
                $("#follow_icon").attr("class","far fa-bell");
                $("#follow strong").text("Inizia a seguire");
              }
            });
          }
          </script>
          @endif--}}
        </div>
        @endauth
      <article class="block-article">
        <div class="block-title">
          <h1 class="text-uppercase">{{ $query->titolo }}</h1>
        </div>
        <p>Scritto da <a href="{{ url($autore->slug) }}">{{ $autore->nome }} {{ $autore->cognome }}</a></p>
          <div class="date-info">
            <span class="date"><i class="far fa-calendar-alt"></i> {{ $date }}</span>
            <span class="time"><i class="far fa-clock"></i> {{ $time }}</span><br/>
          </div>
        <hr/>
        <div class="block-body">
          {!! $query->testo !!}
        </div>
        <div class="both"></div>
        <div class="auth">
          <p>&copy; Produzione riservata</p>
        </div>
        <img src="{{ asset('upload/icons/cc.png') }}" title="{{ trans('Licenza Creative Commons BY SA') }}" alt="License Creative Commons BY SA" />
        @if(!empty($query->tags))
        <div class="block-meta">
          <ul class="meta-tags">
            <span class="fa fa-tags"></span>
            @foreach($tags as $tag)
              <li><a href="{{ url('search/tag/'.$tag) }}">#{{ $tag }}</a></li>
            @endforeach
          </ul>
        </div>
        @endif
      <hr style="border-style:dotted"/>
      <div class="block-footer">
        @if($query->created_at != $query->updated_at)
        <span>Modificato {{ $query->updated_at->diffForHumans() }}</span>
        @endif
        <div class="socials d-flex">
          @if($score->count() > 0)
          <div class="mt-1 mr-2">
            <span id="rcount">{{ $score->sum('score') / $score->count() }} / 5</span>
          </div>
          @endif
          @if(Auth::user() && Auth::user()->id != $query->id_autore)
          <div id="ui-rating-box" >
          @if(!$hasRate)
            <select id="ui-rating-select" name="rating" autocomplete="off">
              <option value=""></option>
              <option value="1">1</option>
              <option value="2">2</option>
              <option value="3">3</option>
              <option value="4">4</option>
              <option value="5">5</option>
            </select>
          @endif
          </div>
          @endif
          @if($hasRate)
          <!-- da sistemare -->
          <div class="rating d-inline">
              <span class="circle less-half"></span>
              <span class="circle"></span>
              <span class="circle"></span>
              <span class="circle"></span>
              <span class="circle"></span>
          </div>
          @endif
          <a id="share_on_facebook" href="https://www.facebook.com/share.php?u={{Request::url()}}" target="_blank">
            <span class="fa-2x fab fa-facebook-square"></span>
          </a>
          <a id="share_on_linkedin" href="https://www.linkedin.com/sharing/share-offsite/?url={{Request::url()}}" target="_blank">
            <span class="fa-2x fab fa-linkedin"></span>
          </a>
          @auth
          <a id="report" href="#report" title="Segnala articolo">
            <span class="fa-2x fas fa-exclamation-triangle"></span>
          </a>
          @endauth
        </div>
      </div>
    </article>
    <hr/>
    {{-- Se gli articolo esistono allora li visualizza --}}
    @include('front.components.article.feeds')

    {{-- Commenti --}}
    @include('front.components.article.comments')

  </div>
  @auth
  <script>
    $("#report").click(function(){
      App.getUserInterface({
      "ui": {
        "header":{"action": "{{route('article/action/report')}}", "method": "GET"},
        "data":{"id": "{{$query->id}}", "selector": "#selOption:checked", "text": "#reasonText"},
        "title": 'Segnala articolo',
        "content": [
          {"type": ["input","radio"], "id": "selOption", "name": "option", "value": "0", "class": "col-md-1", "label": "Notizia Falsa", "required": true},
          {"type": ["input","radio"], "id": "selOption", "name": "option", "value": "1", "class": "col-md-1", "label": "Contenuto di natura sessuale", "required": true},
          {"type": ["input","radio"], "id": "selOption", "name": "option", "value": "2", "class": "col-md-1", "label": "Contenuto violento o che incita all\'odio", "required": true},
          {"type": ["input","radio"], "id": "selOption", "name": "option", "value": "3", "class": "col-md-1", "label": "Promuove il terrorismo o attività criminali", "required": true},
          {"type": ["input","radio"], "id": "selOption", "name": "option", "value": "4", "class": "col-md-1", "label": "Violazione del diritto d\'autore", "required": true},
          {"type": ["input","radio"], "id": "selOption", "name": "option", "value": "5", "class": "col-md-1", "label": "Spam", "required": true},
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
  });
  </script>
  <script src="{{ asset('js/_xs_r.js') }}"></script>
  <script>
  $('#ui-rating-select').barrating('show', {
    theme: 'bars-square',
    showValues: false,
    onSelect: function(value, text) {
      App.query("GET", "{{ route('rate') }}", {id:{{ $query->id }}, rate_value:value}, false, function(data) {
        $(".br-wrapper *").fadeOut(1500, function(){
          //$(this).fadeIn();
          //$("#rcount").html(data.result.rating);
        });
      });
    }
  });
  </script>
  @else
  <script>
  document.getElementById("rating").onclick = function(){guest();};
  function guest(){
    App.getUserInterface({
      "ui": {
        "title": "Ops...",
        "content": [
          {"type": ["h5"], "text": "<a href='{{ url('login') }}'>Accedi</a> o <a href='{{ url('register') }}'>registrati</a> per eseguire questa azione"}
        ]
      }
    });
  }
  </script>
  @endauth
  {{--<script>
  $("#timeline").click(function(){
    App.query("GET", "{{ url('article_history') }}", { id:{{ $query->id }} }, false, function(data) {
     var properties = [];

      Object.keys(data).forEach(i => {
        properties.push(
          {"type": ["h5"], "text": data[i].created_at },
          {"type": ["a"], "href": "/archive/article/read?url={{ $query->slug }}&&token_id="+data[i].token, "text": data[i].no_tags_text},
          {"type": ["hr"]}
        );
      });

      App.getUserInterface({
        "ui": {
          "title": "{{ trans('Cronologia modifiche') }}",
          "content":
            properties

        }
      });

    });
  });
  </script>--}}
  </div>
</div>
@endsection
