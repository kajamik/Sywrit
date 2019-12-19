@extends('front.layout.app')

@php
  $autore = \App\Models\User::find($query->id_autore);
  if($query->id_gruppo > 0) {
    $editore = \App\Models\Editori::find($query->id_gruppo);
  }
@endphp

@section('main')
<style>
.block-body {
  padding: 12px;
  min-height: 18em;
}
.feeds {
  padding: 15px;
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
      <div class="row">
        <div class="col-lg-9 col-md-9">
        <article class="block-article">
          <div class="block-title">
            <h1 class="text-uppercase">{{ $query->titolo }}</h1>
          </div>
          @if($query->id_gruppo > 0)
          <p>@lang('label.article.published_by', ['name' => $editore->name, 'url' => url($editore->slug)])</p>
          @endif
          <p>@lang('label.article.written_by', ['name' => $autore->name.' '.$autore->surname, 'url' => url($autore->slug)])</p>
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
            <p>Tipo di licenza: </p>
            @if($query->license == "1")
            <p>Sywrit Standard</p>
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
          <div class="row pt-5">
            <div class="col-lg-10 col-sm-10 col-xs-10 col-9">
              <div class="reaction-body">
                @include('front.components.article.rate')
              </div>
            </div>

            <div id="share">
              <span class="bs-icon bs-icon-color-sw fa-2x fa fa-share-square"></span>
            </div>
            @if(Auth::guest() || (Auth::user() && $query->id_autore != Auth::user()->id && !Auth::user()->suspended))
              <div id="report" class="ml-4" href="#report" title="Segnala articolo">
                <span class="bs-icon fa-2x fas fa-flag"></span>
              </div>
            @endif
            <script>App.share({'apps': ['clipboard', 'facebook', 'linkedin'],'appendTo': '#share'});</script>
          </div>
        </div>
      </article>
    </div>
      <div class="col-lg-3 col-md-3">
        <div class="position-sticky sticky-top" style="top:63px">
          <div class="card">
            <div class="card-header bg-sw">
              {!! $autore->getRealName() !!}
            </div>
            <div class="card-body">

              <div class="text-center">
                <img src="{{ $autore->getAvatar() }}" alt="Avatar di {{ $autore->name }} {{ $autore->surname }}" />
                <hr/>
                @if(!empty($autore->biography))
                  <p>{!! $autore->biography !!}</p>
                @endif
              </div>
              @if($autore->getSocialLinks()->isNotEmpty())
                @foreach($autore->getSocialLinks() as $value)
                <li><a href="{{ url($value['url']) }}" target="_blank">
                  <i class="{{ $value['icon'] }}"></i> {{ $value['name'] }}
                </a></li>
                @endforeach
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
    {{-- Se gli articolo esistono allora li visualizza --}}
    @include('front.components.article.feeds')

    {{-- Commenti --}}
    @include('front.components.article.comments')

  </div>
  <script>
    @if(Auth::user() && $query->id_autore != Auth::user()->id && !Auth::user()->suspended)
    $("#report").click(function(){
      App.getUserInterface({
      "ui": {
        "header":{"action": "{{ route('article/action/report') }}", "method": "GET"},
        "data":{"id": "{{ $query->id }}", "selector": "#selOption:checked", "text": "#reasonText"},
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
  @elseif(Auth::guest())
  $("#report").click(function(){
      validator();
  });
  @endif
  $(".reaction-body").on('click', '#reaction', function(){
    @if(Auth::user())
      $.get("{{ url('rate') }}", {id: {{ $query->id }}}, function(data) {
        $('.reaction-body').html(data);
      });
    @else
      validator();
    @endif
  });
  </script>

  @if(Auth::guest())
  <script>
  function validator() {
    $.get("{{ url('ajax/auth') }}", {path: '{{ Request::path() }}', callback: 'auth_login'}, function(data) {
      App.getUserInterface({
        "ui": {
          "title": "Benvenuto ospite!!",
          "content": data
        }
      }, true);
    });
  }
  </script>
  @endif
  {{--<link rel="stylesheet" href="{{ asset('lib/noty.css') }}" />
  <script src="{{ asset('lib/noty.js') }}"></script>
  <script>
  new Noty({
    type: 'info',
    layout: 'topRight',
    text: 'Hai sbloccato un nuovo achievement: <br>Scrivi il tuo primo articolo'
}).show();
</script>--}}
  </div>
@endsection
