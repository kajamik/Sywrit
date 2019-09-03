@if($query->count())
@foreach($query as $value)
<div class="card">
  <div class="card-body">
    <div class="d-flex">

      <div class="row">
        <img style="height:4em" class="p-2" src="{{ $value->getUserInfo->getAvatar() }}" />
        <div class="col-md-9 col-12">
          <h4><a class="thumbnail" href="{{ url($value->getUserInfo->slug) }}">{!! $value->getUserInfo->getRealName() !!}</a></h4>
          <span>{{ $value->created_at->diffForHumans() }} {!! $value->permission->tag() !!}</span>
          <hr/>
        </div>
        <div class="ml-3 d-flex col-12">
          {{ $value->text }}
        </div>
        @if(!empty($value->article_id))
        <div class="mt-3 col-12">
        <a href="{{ url('groups/'. $group->id .'/article/'. $value->article_id) }}">
          <div class="card">
            <img class="card-img-top" src="@if($value->cover) {{ $value->cover }} @else {{ asset('upload/no-image.jpg') }} @endif" />
              <div class="card-body">
                <h5 class="card-title" title="{{ $value->article_title }}" data-card-url="/thumbnail/?id={{ $value->id }}&h=profile">{{ str_limit($value->article_title, 33) }}</h5>
              </div>
          </div>
        </a>
        </div>
        @endif
      </div>

      <div class="ml-auto">
        <a data-toggle="dropdown" href="#">
          <i class="fas fa-ellipsis-v"></i>
        </a>
        <div class="dropdown-menu">
          <button id="delete_msg_{{ $value->id }}" class="dropdown-item">Elimina</button>
          @if($value->user_id != Auth::user()->id)
          <button id="report_msg_{{ $value->id }}" class="dropdown-item">Segnala</button>
          @endif
        </div>
      </div>

    </div>

  </div>

  {{-- Risposte--}}

  <div id="conversations_{{ $value->id }}"></div>

  <script>
  updateAnswers();

  function updateAnswers() {
    App.query("get","{{ url('ajax/groups/load-answers') }}", { id: {{ $value->id }} }, false, function(data) {
      if(data) {
        $("#comment_{{ $value->id }}").append(data);
      } else {
        $("#loadAnswers_{{ $value->id }}").remove();
      }
    });
  }
  @if($value->user_id != Auth::user()->id)
  $("#report_msg_{{ $value->id }}").click(function(){
    App.getUserInterface({
    "ui": {
      "header":{"action": "{{ route('comment/action/report' )}}", "method": "GET"},
      "data":{"id": "{{ $value->id }}", "selector": "#selOption:checked", "text": "#reasonText"},
      "title": 'Segnala commento',
      "content": [
        {"type": ["input","radio"], "id":"selOption", "name": "option", "value": "0", "class": "col-md-1", "label": "@lang('form.report_comment_type_0')", "required": true},
        {"type": ["input","radio"], "id":"selOption", "name": "option", "value": "1", "class": "col-md-1", "label": "@lang('form.report_comment_type_1')", "required": true},
        {"type": ["input","radio"], "id":"selOption", "name": "option", "value": "2", "class": "col-md-1", "label": "@lang('form.report_comment_type_2')", "required": true},
        {"type": ["input","radio"], "id":"selOption", "name": "option", "value": "3", "class": "col-md-1", "label": "@lang('form.report_comment_type_3')", "required": true},
        {"type": ["input","radio"], "id":"selOption", "name": "option", "value": "4", "class": "col-md-1", "label": "@lang('form.report_comment_type_4')", "required": true},
        {"type": ["textarea"], "id":"reasonText", "name": "reason", "value": "", "class": "form-control", "placeholder": "@lang('form.motivation_report')"},
        {"type": ["button","submit"], "name": "radio", "class": "btn btn-danger", "text": "@lang('button.send_report')"}
      ],
      "done": function(){
        App.getUserInterface({
          "ui": {
            "title": "@lang('label.report.title')",
            "content": [
              {"type": ["h5"], "text": "@lang('label.report.thanks_for_report')"}
            ]
          }
        });
      }

    } // -- End Interface --
  });
  });
  @else
  $("#reply_{{ $value->id }}").click(function() {
    if($(".replycomment").length) {
      $(".replycomment").remove();
    }
    $("#comment_{{ $value->id }}").append('<div id="reply" class="replycomment"><div class="card-body"><div class="d-flex"><img style="height:4em" class="p-2" src="{{ Auth::user()->getAvatar() }}" />\
    <div class="d-flex flex-grow-1"><textarea class="form-control" placeholder="@lang("form.write_comment")" autofocus></textarea></div></div><div class="py-2 col-md-12"><button type="button" class="btn btn-sw btn-block">@lang("button.send_anwser")</button></div></div></div>');
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
  @endif
  </script>

</div>
@endforeach
@endif
