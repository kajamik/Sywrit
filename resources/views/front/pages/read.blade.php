@extends('front.layout.app')

@section('title', $query->titolo. ' - ')

@php
  $autore = \App\Models\User::find($query->id_autore);
  if($query->id_gruppo > 0) {
    $editore = \App\Models\Editori::find($query->id_gruppo);
  }
@endphp

@section('seo')

    <meta property="og:title" content="{!! $query->titolo !!} - {{ config('app.name') }}" />
    <meta property="og:description" content="{!! str_limit(strip_tags($query->testo), 40, '...') !!}" />
    <meta property="og:type" content="article" />
    <meta property="og:url" content="{{ Request::url() }}" />
    <meta property="og:image" content="{{ asset($query->getBackground()) }}" />
    <meta property="article:published_time" content="{{ $query->created_at }}" />
    <meta property="article:author" content="{{ $autore->name }} {{ $autore->surname }}" />
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
    <div class="publisher-body">
        @auth
        <div class="publisher-info">
          @if($query->id_gruppo > 0)
            @if(Auth::user()->hasMemberOf($query->id_gruppo))
              @include('front.components.article.group_tools')
            @endif
          @else
            @if($query->id_autore == Auth::user()->id)
              @include('front.components.article.my_tools')
            @endif
          @endif
        </div>
        @endauth
      <article class="block-article">
        <div class="block-title">
          <h1 class="text-uppercase">{{ $query->titolo }}</h1>
        </div>
        @if($query->id_gruppo > 0)
        <p>Pubblicato da <a href="{{ url($editore->slug) }}">{{ $editore->name }}</a></p>
        @endif
        <p>Scritto da <a href="{{ url($autore->slug) }}">{{ $autore->name }} {{ $autore->surname }}</a></p>
        @if($query->status)
          <div class="date-info">
            <span class="date"><i class="far fa-calendar-alt"></i> {{ $date }}</span>
            <span class="time"><i class="far fa-clock"></i> {{ $time }}</span><br/>
          </div>
        @else
          <span>Articolo ancora da pubblicare</span>
        @endif
        <hr/>
        <div class="block-body">
          {!! $query->testo !!}
        </div>
        <hr style="border-style:dotted"/>
        <div class="both"></div>
        <div class="auth">
          @if($query->license == "1")
          <p>&copy; Produzione riservata</p>
          @else
          <img src="{{ asset('upload/icons/cc.png') }}" title="{{ trans('Licenza Creative Commons BY SA') }}" alt="License Creative Commons BY SA" />
          @endif
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
      <div class="block-footer">
        @if($query->created_at != $query->updated_at)
        <span>Modificato {{ $query->updated_at->diffForHumans() }}</span>
        @endif
        <div class="socials d-flex">
          @if($score->count() > 0)
          <div class="mt-1 mr-2">
            <span id="rcount">{{ number_format($score->sum('score') / $score->count(), 2) }} / 5</span>
          </div>
          @endif
          @if(Auth::user() && Auth::user()->id != $query->id_autore && !Auth::user()->suspended)
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
          @if(($hasRate || Auth::user() && Auth::user()->id == $query->id_autore) && $score->count())
          <!-- da sistemare -->
          <div class="rating d-flex">
            @for($i = 0; $i < 5; $i++)
              @if( $score->sum('score') / $score->count() > $i)
                @if( floor($score->sum('score') / $score->count()) > $i)
                <span class="circle full"></span>
                @else
                <span class="circle half"></span>
                @endif
              @else
                <span class="circle"></span>
              @endif
            @endfor
          </div>
          @endif
          <a id="share_on_facebook" href="https://www.facebook.com/share.php?u={{Request::url()}}" target="_blank">
            <span class="fa-2x fab fa-facebook-square"></span>
          </a>
          <a id="share_on_linkedin" href="https://www.linkedin.com/sharing/share-offsite/?url={{Request::url()}}" target="_blank">
            <span class="fa-2x fab fa-linkedin"></span>
          </a>
          @if(Auth::user() && $query->id_autore != Auth::user()->id && !Auth::user()->suspended)
          <a id="report" href="#report" title="Segnala articolo">
            <span class="fa-2x fas fa-exclamation-triangle"></span>
          </a>
          @endif
        </div>
      </div>
    </article>
    {{-- Se gli articolo esistono allora li visualizza --}}
    @include('front.components.article.feeds')

    {{-- Commenti --}}
    @include('front.components.article.comments')

  </div>
  @auth
  @if($query->id_autore != Auth::user()->id && !Auth::user()->suspended)
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
          $(this).fadeIn();
          $("#rcount").html(data.result.rating);
        });
      });
    }
  });
  </script>
  @endif
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
