<div class="col-12">
  <p>{{ $count }} commenti</p>
</div>
@if($query->count())
@foreach($query as $value)
<div class="card-body">
    <div class="row">
      <img style="height:4em" class="p-2" src="{{ $value->getUserInfo->getAvatar() }}" />
        <div class="col-md-9 col-9">
          <h4><a class="thumbnail" href="{{ url($value->getUserInfo->slug) }}" data-card-url="/ajax/thumbnail/?id={{ $value->getUserInfo->id }}&h=profile"> {!! $value->getUserInfo->getRealName() !!}</a></h4>
          <div class="mt-2">
            {{ $value->text }}
          </div>
      </div>
      @if(!empty($value->article_id))
      <div class="mt-3 col-12">
      <a href="{{ url('groups/'. $group->id .'/article/'. $value->article_id) }}">
        <div class="card">
          <img class="card-img-top" src="@if($value->cover) {{ $value->cover }} @else {{ asset('upload/no-image.jpg') }} @endif" />
            <div class="card-body">
              <h5 class="card-title thumbnail" title="{{ $value->article_title }}" data-card-url="ajax/thumbnail/?id={{ $value->id }}&h=profile">{{ str_limit($value->article_title, 33) }}</h5>
            </div>
        </div>
      </a>
      </div>
      @endif
      @if(Auth::user() && $value->user_id != Auth::user()->id)
      <script>
      $("#report_comment_a{{ $value->id }}").click(function(){
        App.getUserInterface({
        "ui": {
          "header":{"action": "{{ route('acomment/action/report')}}", "method": "GET"},
          "data":{"id": "{{ $value->id }}", "selector": "#selOption:checked", "text": "#reasonText"},
          "title": '@lang("label.report.comment")',
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
    </script>
      @endif
    </div>

  <div class="col-md-12">
    {{ $value->created_at->diffForHumans() }}
  </div>
</div>
@endforeach
@endif
<hr/>
<div class="p-0 col-md-12">
  <div class="d-flex">
    <img style="height:4em" class="p-2 d-none d-md-block" src="{{ Auth::user()->getAvatar() }}" />
    <div class="dialog_box">
      <div class="dialog_editor">
        <div id="d_325" class="dialog_editor__editable" contenteditable="true" role="textarea" data-placeholder="Invia un commento"></div>
      </div>
    </div>
  </div>
</div>
@if($count > 0)
<button type="button" class="btn btn-sw btn-block">
  Carica altre risposte
</button>
@endif
