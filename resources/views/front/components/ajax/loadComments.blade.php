@if($query->count())
@foreach($query as $value)
<div class="card">
  <div class="card-header bg-dark">
    <a href="{{ url($value->getUserInfo->slug) }}" class="text-light">{{ $value->getUserInfo->nome }} {{ $value->getUserInfo->cognome }}</a>
    <div class="float-right text-light">
      <span>{{ $value->created_at->diffForHumans() }}</span>
    </div>
  </div>
  <div class="card-body">
    <div class="d-flex">
      <img style="height:4em" class="p-2" src="{{ asset($value->getUserInfo->getAvatar()) }}" />
        <div class="d-flex flex-grow-1">
          {{ $value->text }}
        </div>
        @auth
        <div class="report d-inline float-right">
          <a data-toggle="dropdown" href="#">
            <span class="fas fa-ellipsis-v"></span>
          </a>
          <div class="dropdown-menu">
            <a id="report_comment_{{ $value->id }}" class="dropdown-item" href="#report">{{ trans('Segnala commento') }}</a>
          </div>
        </div>
        <script>
        $("#report_comment_{{ $value->id }}").click(function(){
          App.getUserInterface({
          "ui": {
            "header":{"action": "{{route('article/action/report')}}", "method": "POST"},
            "data":{"id": "{{ $value->id }}", "_token": "{{ csrf_token() }}", "selector": "#selOption:checked", "text": "#reasonText"},
            "title": 'Segnala commento',
            "content": [
              {"type": ["input","radio"], "id":"selOption", "name": "option", "value": "0", "class": "col-md-1", "label": "Contenuto di natura sessuale", "required": true},
              {"type": ["input","radio"], "id":"selOption", "name": "option", "value": "1", "class": "col-md-1", "label": "Contenuto violento o che incitano all\'odio", "required": true},
              {"type": ["input","radio"], "id":"selOption", "name": "option", "value": "2", "class": "col-md-1", "label": "Promuove il terrorismo o attività criminali", "required": true},
              {"type": ["input","radio"], "id":"selOption", "name": "option", "value": "3", "class": "col-md-1", "label": "Spam", "required": true},
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
        @endauth
    </div>
    <hr/>
    @auth
    <div class="col-md-12">
      <button id="reply_{{ $value->id }}" class="btn btn-link">Rispondi</button>
    </div>
    @endauth
  </div>

  {{-- Risposte--}}

  <div id="comment_{{ $value->id }}"></div>

  @auth
  <script>

    updateAnswers();

    $("#reply_{{ $value->id }}").click(function() {
      if($(".replycomment").length) {
        $(".replycomment").remove();
      }
      $("#comment_{{ $value->id }}").append('<div class="replycomment"><div class="card-body"><div class="d-flex"><img style="height:4em" class="p-2" src="{{ asset($value->getUserInfo->getAvatar()) }}" />\
      <div class="d-flex flex-grow-1"><textarea class="form-control" placeholder="{{ trans("Scrivi un commento") }}"></textarea></div></div><div class="py-2 col-md-12"><button type="button" class="btn btn-dark btn-block">Invia Risposta</button></div></div></div>');

      $("button[type=button]").click(function() {
        App.query("get","{{ url('send-answers') }}", { id: {{ $value->id }}, post: $(".replycomment textarea").val() }, false, function(data) {
          $("#comment_{{ $value->id }}").prepend(data);
        });
      });

    });

    function updateAnswers(query = 1) {
      App.query("get","{{ url('load-answers') }}", { id: {{ $value->id }} }, false, function(data) {
        $("#comment_{{ $value->id }}").append(data);
      });
    }

  </script>
  @else
  <script>
  $(function() {
    App.query("get","{{ url('load-answers') }}", { id: {{ $value->id }} }, false, function(data) {
      $("#comment_{{ $value->id }}").append(data);
    });
  });
  </script>
  @endif

</div>
@endforeach
@endif
