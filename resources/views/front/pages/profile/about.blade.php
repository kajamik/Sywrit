@extends('front.layout.app')

@section('main')
<style>
#nav > li {
  display: inline-block;
  margin-top: 5px;
  margin-bottom: 10px;
  font-size: 20px;
}
#nav > li:not(:last-child)::after {
  content: '\00a0|';
}
._ou {
  cursor: pointer;
}
#customMsg {
  min-height: 200px;
}
</style>
  <div class="publisher-home">
    <div class="publisher-header" style="background-image: url({{ $query->getBackground() }});border-radius:4px 4px 0 0;">
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
      <ul id='nav'>
        <li><a href="{{ url($query->slug) }}">@lang('label.menu.profile')</a></li>
        <li><a href="{{ url($query->slug.'/about') }}">@lang('label.menu.contact')</a></li>
        @if(\Auth::user() && \Auth::user()->id == $query->id)
        <li><a href="{{ url($query->slug.'/archive') }}">@lang('label.menu.saved_articles')</a></li>
        @endif
      </ul>
    </nav>
      <div class="publisher-body">
        <hr/>
        <div class="publisher-info">
        {{--<div class="col-md-12">
          <div class="publisher-bar" data-pub-text="#followers">
              <span id="followers">{{ $query->followers_count }}</span>
              Followers
          </div>
        </div>--}}
        @if(Auth::user() && Auth::user()->id != $query->id)
        <div class="col-md-12">
          <a id="report" href="#report">
            @lang('label.report.user')
          </a>
        </div>
        <script>
          $("#report").click(function(){
            App.getUserInterface({
            "ui": {
              "header":{"action": "{{route('article/action/report')}}", "method": "GET"},
              "data":{"id": "{{$query->id}}", "selector": "#selOption:checked", "text": "#reasonText"},
              "title": '@lang("label.report.user")',
              "content": [
                {"type": ["input","radio"], "id": "selOption", "name": "option", "value": "0", "class": "col-md-1", "label": "@lang('form.report_user_type_0')", "required": true},
                {"type": ["input","radio"], "id": "selOption", "name": "option", "value": "1", "class": "col-md-1", "label": "@lang('form.report_user_type_1')", "required": true},
                {"type": ["input","radio"], "id": "selOption", "name": "option", "value": "2", "class": "col-md-1", "label": "@lang('form.report_user_type_2')", "required": true},
                {"type": ["input","radio"], "id": "selOption", "name": "option", "value": "3", "class": "col-md-1", "label": "@lang('form.report_user_type_3')", "required": true},
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
        <hr/>
        @endif
      </div>

      <style>
      h2 {
        padding: 8px
        border-radius: 4px;
        text-transform: uppercase;
        font-size: 23px;
      }
      h2 + div {
        padding: 15px;
      }
      address > a, address > a:hover {
        text-decoration: underline;
      }
      </style>

        <div class="publisher-content">
          <div class="py-3">
            {{--
            <div class="col-12">
              <h2>Followers</h2>
              <div class="col-lg-12">
                <div class="row">
                  @foreach($followers as $value)
                  @php
                    $user = \App\Models\User::where('id',$value)->first();
                  @endphp
                  <div class="v_card col-lg-2 col-sm-8 col-xs-12">
                    <a href="{{ url($user->slug) }}">
                      <div class="card">
                        <img class="card-img-top" src="{{ $user->getAvatar() }}" alt="Avatar">
                        <div class="card-body">
                          <strong class="card-title">{{ $user->name }} {{ $user->surname }}</strong>
                        </div>
                      </div>
                    </a>
                  </div>
                  @endforeach
                </div>
              </div>
            </div>
            --}}
            @if(!empty($query->facebook) || !empty($query->instagram) || !empty($query->linkedin))
            <div class="col-12">
            <h2>Socials Link</h2>
            <div class"col-lg-12">
              @if(!empty($query->facebook))
              <address>
                Facebook: <a href="https://facebook.com/{{ $query->facebook }}" target="_blank">{{ $query->facebook }}</a>
              </address>
              @endif
              @if(!empty($query->instagram))
              <address>
                Instagram: <a href="https://instagram.com/{{ $query->instagram }}" target="_blank">{{ $query->instagram }}</a>
              </address>
              @endif
              @if(!empty($query->linkedin))
              <address>
                Linkedin: <a href="https://linkedin.com/in/{{ $query->linkedin }}" target="_blank">{{ $query->linkedin }}</a>
              </address>
              @endif
            </div>
            </div>
            @else
              <p>@lang('label.notice.user_no_contact')</p>
            @endif
          </div>
    </div>
  </div>
</div>
@endsection
