@if($query->count())
<div class="card ml-3 mr-3">
@foreach($query as $value)
  <div class="card-body">
    <div class="d-flex">
      <img style="height:4em" class="p-2" src="{{ $value->getUserInfo->getAvatar() }}" />
        <div class="d-flex flex-grow-1">
          {{ $value->text }}
        </div>
        @if(Auth::user() && Auth::user()->id != $value->user_id)
        <div class="report d-inline float-right">
          <a data-toggle="dropdown" href="#">
            <span class="fas fa-ellipsis-v"></span>
          </a>
          <div class="dropdown-menu">
            <a id="report_comment_a{{ $value->id }}" class="dropdown-item" href="#report">{{ trans('Segnala commento') }}</a>
          </div>
        </div>
        <script>
        $("#report_comment_a{{ $value->id }}").click(function(){
          App.getUserInterface({
          "ui": {
            "header":{"action": "{{ route('acomment/action/report')}}", "method": "GET"},
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
        @endif
    </div>
    <hr/>
    <div class="col-md-12">
      {{ $value->created_at->diffForHumans() }}
      <a href="{{ url($value->getUserInfo->slug) }}">
        <span>{!! $value->getUserInfo->getRealName() !!}</span>
      </a>
    </div>
  </div>
@endforeach
</div>
@endif
