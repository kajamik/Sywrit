@if($query->count())
@foreach($query as $value)
<div class="card">
  <div class="card-body">
    <div class="d-flex">

      <div class="row">
        <img style="height:4em" class="p-2" src="{{ $value->getUserInfo->getAvatar() }}" />
        <div class="col-md-9 col-9">
          <h4><a class="thumbnail" href="{{ url($value->getUserInfo->slug) }}" data-card-url="/ajax/thumbnail/?id={{ $value->getUserInfo->id }}&h=profile">{!! $value->getUserInfo->getRealName() !!}</a></h4>
          <span>{{ $value->created_at->diffForHumans() }} {{-- $value->memberInfo->tag() --}}</span>
        </div>
        <div class="ml-3 py-3 d-flex col-12">
          {{ $value->text }}
        </div>
        @if(!empty($value->article_id))
        <div class="mt-3 col-12">
        <a href="{{ url('groups/'. $group->id .'/article/'. $value->article_id) }}">
          <div class="card">
            <img class="card-img-top" src="@if($value->cover) {{ $value->cover }} @else {{ asset('upload/no-image.jpg') }} @endif" />
              <div class="card-body">
                <h5 class="card-title" title="{{ $value->article_title }}" data-card-url="/thumbnail/?id={{ $value->id }}&h=profile">{{ str_limit($value->article_title, 33) }}</h5>
                <p>Di utente</p>
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
          @if(Auth::user() && $value->user_id == Auth::user()->id)
          <button id="delete_msg_{{ $value->id }}" class="dropdown-item">Elimina</button>
          @endif
          @if((Auth::user() && $value->user_id != Auth::user()->id) || !Auth::user())
          <button id="report_msg_{{ $value->id }}" class="dropdown-item">Segnala</button>
          @endif
        </div>
      </div>

    </div>

  </div>

  <hr/>

  {{-- Risposte--}}

  <div id="conversations_{{ $value->id }}"></div>

  <script>
  updateAnswers();
  function updateAnswers() {
    $.get("{{ url('ajax/groups/loadMessages') }}",{ id: {{ $value->id }}, q: this.q, answer: 1 }, function(data) {
      if(data) {
        $("#conversations_{{ $value->id }}").append(data);
        q++;
      } else {
        $("#loadComments").remove();
      }
    });
  }
  @if(Auth::user() && $value->user_id != Auth::user()->id)
  $("#report_msg_{{ $value->id }}").click(function(){
    App.getUserInterface({
    "ui": {
      "header":{"action": "{{ route('comment/action/report') }}", "method": "GET"},
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
  @elseif(Auth::user() && ($value->user_id == Auth::user()->id || Auth::user()->groupInfo->hasStaff()))
  $("#delete_msg_{{ $value->id }}").click(function(){
    App.getUserInterface({
      "ui": {
        "theme": "",
        "header":{"action": "{{ url('ajax/groups/post') }}", "method": "GET"},
        "data":{"id": "{{ $value->id }}", "action": "delete"},
        "title": "Eliminazione commento",
        "content": [
          {"type": ["h5"], "text": "Confermi di eliminare questo commento?"},
          {"type": ["button"], "class": "btn btn-sw", "text": "Conferma"}
        ],
        "done": function(){
        }
      }
    });
  });
  @endif
  </script>

</div>
@endforeach
@endif
