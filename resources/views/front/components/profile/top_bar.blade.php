<style>
#nav > li {
  display: inline-block;
  margin-top: 5px;
  margin-bottom: 10px;
  font-size: 20px;
}
#nav > li:not(:last-child)::after {
  content: ' |\00a0';
}
#nav > li.active a {
  color: #fff;
}
</style>
<div class="publisher-home">
  <div class="publisher-header" style="background-image: url({{ $query->getBackground() }})">
    <div class="container">
      <div class="publisher-logo">
        <div class="row">
          <div class="d-inline">
            <img src="{{ $query->getAvatar() }}" alt="Logo">
          </div>
          <div class="ml-2 mt-2 info">
            <span>
              {!! $query->getRealName() !!}
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
    <nav class="publisher-nav">
      <ul id='nav' class="row">
        <li @if(!isset($slug)) class="active" @endif><a href="{{ url($query->slug) }}">@lang('label.menu.profile')</a></li>
        <li><a href="{{ url($query->slug.'/about') }}">@lang('label.menu.contact')</a></li>
        @if(\Auth::user() && \Auth::user()->id == $query->id)
        <li><a href="{{ url('articles') }}">@lang('label.menu.saved_articles')</a></li>
        @endif
      </ul>
    </nav>
    <hr/>
      <div class="publisher-body">
        <div class="publisher-info">
          @yield('profile::bio')
          {{--<div class="col-md-12">
            @if(!$query->id_gruppo)
            <p>Editore individuale</p>
            @else
            <p>Editore presso
                @foreach($gruppi as $value)
                  <a class="text-underline" href="{{ url($value->slug) }}"> {{ $value->name }}</a>
                @endforeach
            </p>
            @endif
          </div>--}}
          <div class="col-md-12">
            <span class="fa fa-newspaper"></span> {{ $count }}
          </div>
          {{--
          @if(\Auth::user() && \Auth::user()->id != $query->id)
          <div class="col-md-12">
            @if(Auth::user()->haveGroup() && Auth::user()->hasFoundedGroup() && !Auth::user()->suspended)
            <div class="_ou">
              <a id="usr_invite" href="#">
                <i class="fas fa-envelope"></i> <span>Assumi come collaboratore</span>
              </a>
            </div>
            <script>
            var array = {!! json_encode(Auth::user()->getPublishersInfo()) !!};
            var properties = [];
            Object.keys(array).forEach(i => {
              properties.push({"type": ["option"], "value": array[i].id, "text": array[i].name});
            });

              $("#usr_invite").click(function(){
                App.getUserInterface({
                  "ui": {"title": "Invito collaborazione",
                  "header": {"action": "{{ url('group/action/invite') }}", "method": "GET"},
                  "data": { user_id: "{{ $query->id }}", selector: "#publisherSelector###val" },
                  "content": [
                  {"type": ["h6"], "text": "Seleziona la redazione"},
                  {"type": [ {"select": properties} ], "class": "form-control", "name": "publisherSelector" },
                  {"type": ["button","submit"], "class": "btn btn-info", "text": "Invia Richiesta"}
                ],
              "done": function(d){App.getUserInterface({"ui": {"title": "Info Redazione","content": [{"type": ["h5"], "text": d.message}]}});}}});
            });
            </script>
            @endif
          </div>
        @endif
        --}}
        @if(Auth::user() && Auth::user()->id != $query->id && !Auth::user()->suspended)
        <div class="col-md-12">
          <button id="report" class="btn btn-link">
            @lang('label.report.user')
          </button>
        </div>
        <script>
          $("#report").click(function(){
            App.getUserInterface({
            "ui": {
              "header":{"action": "{{ route('user/action/report') }}", "method": "GET"},
              "data":{"id": "{{$query->id}}", "selector": "#selOption:checked###val", "text": "#reasonText###val"},
              "title": '@lang("label.report.user")',
              "content": [
                {"type": ["input","radio"], "id": "selOption", "name": "option", "value": "0", "class": "col-md-1", "label": "@lang('form.report_type_0')", "required": true},
                {"type": ["input","radio"], "id": "selOption", "name": "option", "value": "1", "class": "col-md-1", "label": "@lang('form.report_type_1')", "required": true, "data-script": "info", "data-text": "rr"},
                {"type": ["input","radio"], "id": "selOption", "name": "option", "value": "2", "class": "col-md-1", "label": "@lang('form.report_type_2')", "required": true},
                {"type": ["input","radio"], "id": "selOption", "name": "option", "value": "3", "class": "col-md-1", "label": "@lang('form.report_type_3')", "required": true},
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
      @if($query->suspended)
      <div class="col-md-12">
        <div class="alert alert-dark">
          <h3>@lang('label.notice.account_suspended', ['standards' => url("page/standards")])</h3>
        </div>
      </div>
      @endif
      <hr/>
