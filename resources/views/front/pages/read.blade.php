@extends('front.layout.app')

@php
  $autore = \App\Models\User::find($query->id_autore);
  if($query->id_gruppo > 0) {
    $editore = \App\Models\Editori::find($query->id_gruppo);
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
  <div class="publisher-home">
    <div class="publisher-body">
        @auth
        <div class="publisher-info">
          @if($query->id_gruppo > 0 && Auth::user()->hasMemberOf($query->id_gruppo) || $query->id_autore == Auth::user()->id)
            @include('front.components.article.options')
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
        <div class="date-info">
          <span class="date"><i class="far fa-calendar-alt"></i> {{ $date }}</span>
          <span class="time"><i class="far fa-clock"></i> {{ $time }}</span><br/>
        </div>
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
        <div class="row pt-5">
          <div class="col-lg-10 col-sm-12 col-xs-12">

            @if($score->count() > 0)
              <span id="rcount" class="pr-3">{{ number_format($score->sum('score') / $score->count(), 2) }} / 5</span>
              @if($hasRate || Auth::user() && Auth::user()->id == $query->id_autore)
              <div class="rating">
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
            @endif

            @if(!Auth::check() || (!$hasRate && Auth::user()->id != $query->id_autore && !Auth::user()->suspended))
            <div>Valuta articolo</div>
            <div id="ui-rating-box">
              <select id="ui-rating-select" name="rating" autocomplete="off">
                <option value=""></option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
              </select>
            </div>
            @endif

        </div>
        <div class="socials">
          <div class="col-lg-12 col-sm-12 col-xs-12">
            <a id="share_on_facebook" href="https://www.facebook.com/share.php?u={{Request::url()}}" target="_blank">
              <span class="fa-2x fab fa-facebook-square"></span>
            </a>
            <a id="share_on_linkedin" href="https://www.linkedin.com/sharing/share-offsite/?url={{Request::url()}}" target="_blank">
              <span class="fa-2x fab fa-linkedin"></span>
            </a>
            @if(Auth::user() && $query->id_autore != Auth::user()->id && !Auth::user()->suspended)
            <a id="report" class="ml-4" href="#report" title="Segnala articolo">
              <span class="fa-2x fas fa-flag"></span>
            </a>
          </div>
          @endif
        </div>
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
  @endif
  @endauth
  @if(Auth::guest() || (!$hasRate && Auth::user()->id != $query->id_autore && !Auth::user()->suspended))
  <script src="{{ asset('js/_xs_r.min.js') }}"></script>
  <script>
  $('#ui-rating-select').barrating('show', {
    theme: 'bars-square',
    showValues: false,
    onSelect: function(value, text) {
      App.query("GET", "{{ route('rate') }}", {id: {{ $query->id }}, rate_value:value}, false, function(data) {
        if(data.success) {
          $(".br-wrapper *").fadeOut();
        } else {
          App.getUserInterface({
            "ui": {
              "title": "Avviso",
              "content": [
                {"type": ["h5"], "text": "Effettua l'accesso per valutare questo articolo."}
              ]
            }
          });
        }
      });
    }
  });
  </script>
  @endif
  </div>
@endsection
