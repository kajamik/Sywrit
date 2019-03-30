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
    </div>
    <hr/>
    <div class="col-md-12">
      <button id="reply_{{ $value->id }}" class="btn btn-link">Rispondi</button>
    </div>
  </div>

  {{-- Risposte--}}

  <div id="comment_{{ $value->id }}"></div>

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

</div>
@endforeach
@endif
