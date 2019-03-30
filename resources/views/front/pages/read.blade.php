@extends('front.layout.app')

@section('title', $query->titolo. ' -')

@php
  $autore = \App\Models\User::find($query->id_autore);
  if($query->id_gruppo > 0)
    $editore = \App\Models\Editori::find($query->id_gruppo);

  $collection = collect(explode(',',$autore->followers));
  if(Auth::user() && $collection->some(\Auth::user()->id)){
    $follow = true;
  }else{
    $follow = false;
  }

  $rating = collect(explode(',',$query->rated));
  if(Auth::user() && $rating->some(Auth::user()->id)) {
    $hasRate = true;
  } else {
    $hasRate = false;
  }
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
    @if(!empty($editore))
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
    @endif
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
          @if(Auth::user() && $query->id_autore != \Auth::user()->id)
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
            App.query('GET','{{ url("follow?q=false") }}', {id: '{{ $query->id_autore }}'}, false, function(data){
              if(data.result){
                $("#follow_icon").attr("class","fa fa-bell");
                $("#follow strong").text("Smetti di seguire");
              }else{
                $("#follow_icon").attr("class","fas fa-bell");
                $("#follow strong").text("Inizia a seguire");
              }
            });
          }
          </script>
          @endif
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
        <div class="socials">
          @if(Auth::user() && Auth::user()->id != $query->id_autore)
          @if(!$hasRate)
          <div id="ui-rating-box" class="float-left d-inline">
            <a href="#">
              <select id="ui-rating-select" name="rating" autocomplete="off">
                <option value=""></option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
              </select>
            </a>
          </div>
          @else
          <span>Hai già votato</span>
          @endif
          @endif
          <a id="share_on_facebook" href="https://www.facebook.com/share.php?u={{Request::url()}}">
            <span class="fa-2x fab fa-facebook-square"></span>
          </a>
          <a id="share_on_linkedin" href="https://www.facebook.com/share.php?u={{Request::url()}}">
            <span class="fa-2x fab fa-linkedin"></span>
          </a>
          @auth
          <a data-toggle="dropdown" href="#">
            <span class="fa-2x fas fa-ellipsis-v"></span>
          </a>
          <div class="dropdown-menu">
            <a id="report" class="dropdown-item" href="#report">Segnala Articolo</a>
          </div>
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
  <script src="{{ asset('js/_xs_r.js') }}"></script>
  <script>
  $('#ui-rating-select').barrating('show', {
    theme: 'bars-square',
    showValues: true,
    showSelectedRating: false,
    onSelect: function(value, text) {
      App.query("GET", "{{ route('rate') }}", {id:{{ $query->id }}, rate_value:value}, false, function() {
        $(".br-wrapper").fadeOut(1500, function(){
          $(this).fadeIn();
          $(this).text("Grazie per aver votato!!");
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
  </div>
</div>
@endsection
