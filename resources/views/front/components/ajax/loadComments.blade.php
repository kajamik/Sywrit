@if($query->count())
@foreach($query as $n => $value)
@php
  $count = \DB::table('answer_comments')->where('comment_id', $value->id)->count();
@endphp
<div class="card">
  <div class="card-header bg-sw">
    <a href="{{ url($value->getUserInfo->slug) }}" class="text-light">{!! $value->getUserInfo->getRealName() !!}</a>
    <div class="float-right text-light">
      {{ $value->created_at->diffForHumans() }}
    </div>
  </div>
  <div class="card-body">
    <div class="d-flex">
      <img style="height:4em" class="p-2" src="{{ $value->getUserInfo->getAvatar() }}" />
      <div class="d-flex">
        {{ $value->text }}
      </div>

      @if(Auth::user() && Auth::user()->id != $value->user_id && !Auth::user()->suspended)
      <div class="ml-auto">
        <a data-toggle="dropdown" href="#">
          <i class="fas fa-ellipsis-v"></i>
        </a>
        <div class="dropdown-menu">
          <a id="report_comment_d{{ $value->id }}" class="dropdown-item" href="#report">{{ trans('Segnala commento') }}</a>
        </div>
      </div>
    </div>
      <hr/>
      <div class="col-md-12">
        <button id="reply_{{ $value->id }}" class="btn btn-link">Rispondi</button>
      </div>
      <script>
      $("#report_comment_d{{ $value->id }}").click(function(){
        App.getUserInterface({
        "ui": {
          "header":{"action": "{{ route('comment/action/report' )}}", "method": "GET"},
          "data":{"id": "{{ $value->id }}", "selector": "#selOption:checked", "text": "#reasonText"},
          "title": 'Segnala commento',
          "content": [
            {"type": ["input","radio"], "id":"selOption", "name": "option", "value": "0", "class": "col-md-1", "label": "Contenuto di natura sessuale", "required": true},
            {"type": ["input","radio"], "id":"selOption", "name": "option", "value": "1", "class": "col-md-1", "label": "Contenuto violento o che incitano all\'odio", "required": true},
            {"type": ["input","radio"], "id":"selOption", "name": "option", "value": "2", "class": "col-md-1", "label": "Molestie o bullismo", "required": true},
            {"type": ["input","radio"], "id":"selOption", "name": "option", "value": "3", "class": "col-md-1", "label": "Promuove il terrorismo o attività criminali", "required": true},
            {"type": ["input","radio"], "id":"selOption", "name": "option", "value": "4", "class": "col-md-1", "label": "Spam", "required": true},
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
      @else
        </div>
      @endif

    </div>

  </div>

  {{-- Risposte--}}

  <div id="comment_{{ $value->id }}"></div>

  <script>
  updateAnswers();

  function updateAnswers() {
    App.query("get","{{ url('load-answers') }}", { id: {{ $value->id }} }, false, function(data) {
      if(data) {
        $("#comment_{{ $value->id }}").append(data);
      } else {
        $("#loadAnswers_{{ $value->id }}").remove();
      }
    });
  }
  </script>
  @if(Auth::user() && Auth::user()->id != $value->user_id && !Auth::user()->suspended)
  <script>
    $("#reply_{{ $value->id }}").click(function() {
      if($(".replycomment").length) {
        $(".replycomment").remove();
      }
      $("#comment_{{ $value->id }}").append('<div id="reply" class="replycomment"><div class="card-body"><div class="d-flex"><img style="height:4em" class="p-2" src="{{ Auth::user()->getAvatar() }}" />\
      <div class="d-flex flex-grow-1"><textarea class="form-control" placeholder="{{ trans("Scrivi un commento") }}" autofocus></textarea></div></div><div class="py-2 col-md-12"><button type="button" class="btn btn-sw btn-block">Invia Risposta</button></div></div></div>');
      $("html, body").animate({ scrollTop: $('#reply').offset().top }, 1000);
      $("button[type=button]").click(function() {
        App.query("get","{{ url('send-answers') }}", { id: {{ $value->id }}, post: $(".replycomment textarea").val() }, false, function(data) {
          if($("#comment_{{ $value->id }} > .card").length == 0) {
            $("<div/>").addClass("card").insertBefore(".replycomment");
          }
          $("#comment_{{ $value->id }} > .card").append(data);
        });
      });

    });

  </script>
  @endif

  @if($count > 6)
  <div class="offset-md-5">
    <button id="loadAnswers_{{ $value->id }}" class="btn btn-light mb-5">Carica altre risposte</button>
  </div>
  @endif

</div>
@endforeach
@endif
