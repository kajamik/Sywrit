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
            <a id="report_comment_a{{ $value->id }}" class="dropdown-item" href="#report">{{ trans('label.report_comment') }}</a>
          </div>
        </div>
        <script>
        $("#report_comment_a{{ $value->id }}").click(function(){
          App.getUserInterface({
          "ui": {
            "header":{"action": "{{ route('acomment/action/report')}}", "method": "GET"},
            "data":{"id": "{{ $value->id }}", "selector": "#selOption:checked", "text": "#reasonText"},
            "title": '{{ __("label.report.comment") }}',
            "content": [
              {"type": ["input","radio"], "id":"selOption", "name": "option", "value": "0", "class": "col-md-1", "label": "{{ __('form.report_comment_type_0') }}", "required": true},
              {"type": ["input","radio"], "id":"selOption", "name": "option", "value": "1", "class": "col-md-1", "label": "{{ __('form.report_comment_type_1') }}", "required": true},
              {"type": ["input","radio"], "id":"selOption", "name": "option", "value": "2", "class": "col-md-1", "label": "{{ __('form.report_comment_type_2') }}", "required": true},
              {"type": ["input","radio"], "id":"selOption", "name": "option", "value": "3", "class": "col-md-1", "label": "{{ __('form.report_comment_type_3') }}", "required": true},
              {"type": ["input","radio"], "id":"selOption", "name": "option", "value": "4", "class": "col-md-1", "label": "{{ __('form.report_comment_type_4') }}", "required": true},
              {"type": ["textarea"], "id":"reasonText", "name": "reason", "value": "", "class": "form-control", "placeholder": "{{ __('form.motivation_report') }}"},
              {"type": ["button","submit"], "name": "radio", "class": "btn btn-danger", "text": "{{ __('button.send_report') }}"}
            ],
            "done": function(){
              App.getUserInterface({
                "ui": {
                  "title": "{{ __('label.report.title') }}",
                  "content": [
                    {"type": ["h5"], "text": "{{ __('label.report.thanks_for_report') }}"}
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
