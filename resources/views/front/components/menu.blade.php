@php
  $categorie = \DB::table('article_category')->select('article_category.slug as c_slug')
                                            ->whereExists(function($query) {
                                              $query->select(DB::raw(1))
                                                    ->from('articoli')
                                                    ->whereRaw('articoli.topic_id = article_category.id');
                                            })
                                            ->orderBy('name','asc')->get();
@endphp
<div class="navbar">
    <div class="col-3">
      <a href="{{url('/')}}" class="brand">
        <svg width="55" height="55" viewBox="0 -5 109 140">
          <g>
            <path style="fill:#c7bde5;stroke-width:0.76800001" d="M 30.908444,94.26849 C 13.9088,77.264955 0,63.007355 0,62.584932 0,61.585674 61.592629,0 62.592,0 c 1.003932,0 62.592,61.588068 62.592,62.592 0,1.008492 -61.590462,62.592 -62.599068,62.592 -0.422424,0 -14.676843,-13.91198 -31.676488,-30.91551 z" />
            <path
              style="fill:#000000;stroke-width:0.76800001" d="M 30.908444,94.26849 C 13.9088,77.264955 0,63.007355 0,62.584932 0,61.585674 61.592629,0 62.592,0 c 1.003932,0 62.592,61.588068 62.592,62.592 0,1.008492 -61.590462,62.592 -62.599068,62.592 -0.422424,0 -14.676843,-13.91198 -31.676488,-30.91551 z M 91.20384,33.98779 62.59963,5.3759498 33.98779,33.98016 5.3759498,62.58437 33.98016,91.196214 62.58437,119.80805 91.196214,91.20384 119.80805,62.59963 Z M 50.198045,97.550254 C 40.148154,94.91553 33.089504,90.207245 34.155262,86.849341 c 0.733275,-2.310343 2.254037,-1.99352 6.773691,1.411185 9.106095,6.859707 24.35982,9.406026 24.348176,4.064456 C 65.272376,90.144637 62.801464,86.823713 54.583003,77.952 34.269059,56.023378 30.999086,49.409026 35.598988,39.552 41.436551,27.042821 59.929495,21.386515 77.351363,26.781497 c 7.116664,2.203798 10.33986,4.41676 10.051077,6.900801 -0.347536,2.989355 -2.464666,2.916032 -7.053297,-0.244275 C 72.414634,27.973314 62.087684,26.639749 59.86503,30.792816 58.380657,33.566394 61.212364,37.52047 74.115523,50.691597 88.868436,65.750895 90.986051,69.128838 90.96414,77.568 c -0.0094,3.606735 -0.472029,5.864648 -1.667298,8.136714 -5.714488,10.862538 -22.940956,16.081546 -39.098797,11.84554 z" />
          </g>
        </svg>
      </a>
    </div>

  <div class="nav">
        <ul class="user-navbar container">
          <li>
            <form action="{{ url('search/') }}" method="get">
              <div class="ty-search">
                <div class="d-flex">
                  <input id="search_query" name="q" type="text" placeholder="@lang('form.search')" onkeyup="fetch_live_search(this.value);" autocomplete="off"/>
                  <div class="set d-flex">
                    <button id="search" type="submit" role="button" aria-label="true">
                      <span class="fa fa-search"></span>
                    </button>
                  </div>
                </div>
                <div class="data-list"></div>
              </div>
            </form>
            <script>
            $("form").submit(function(){
              if($.trim($("form input[name=q]").val()).length > 0) {
                window.location = "{{ url('search') }}/" + $("form input[name=q]").val();
              }
              return false;
            });
            </script>
          </li>
          <li>
            <a id="support" href="#support">
              <i class="fa fa-question-circle" aria-hidden="true" title="@lang('label.support_contact')"></i>
            </a>
          </li>
          <script>
          $("#support").click(function(){
            App.getUserInterface({
            "ui": {
              "header":{"action": "{{ url('action/support') }}", "method": "GET"},
              @auth
              "data":{"text": "#text", "selector": "#selector"},
              @else
              "data":{ "text": "#text", "email": "#email", "selector": "#selector"},
              @endif
              "title": '@lang("label.support_contact")',
              "content": [
                @guest
                {"type": ["input","email"], "id": "email", "name": "email", "class": "form-control", "placeholder": "Indirizzo e-mail", "required": true},
                @endif
                {"type": [{"select": [{"type": ["option"], "value": "1", "text": "@lang('form.select_support')"},{"type": ["option"], "value": "2", "text": "@lang('form.select_feedback')"},]}], "id": "selector", "name": "selector", "class": "form-control"},
                {"type": ["textarea"], "id":"text", "name": "message", "value": "", "class": "form-control", "placeholder": "@lang('form.write_message')", "required": true },
                {"type": ["button","submit"], "id": "message", "class": "btn btn-info", "text": "@lang('button.send_message')"}
              ],
              "done": function(){
                App.getUserInterface({
                  "ui": {
                    "title": "@lang('label.report_header')",
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
          @auth
          <li>
            <a href="{{url('write')}}">
              <i class="fa fa-newspaper" aria-hidden="true" title="@lang('label.create_article')"></i>
            </a>
          </li>
          @endauth
          <li class="dropdown">
            <a href="#" data-toggle="dropdown" role="button" aria-expanded="false" title="@lang('label.categories.title')">
              <span class="fa-1x fa fa-th"></span>
            </a>
            <div class="dropdown-menu ml-5">
              @foreach($categorie as $value)
              <a class="dropdown-item" href="{{ url('topic/'.$value->c_slug) }}">
                @lang('label.categories.'. $value->c_slug)
              </a>
              @endforeach
            </div>
          </li>
          @auth
          <li class="dropdown">
          <a id="notification" href="#" data-toggle="dropdown" onclick="fetch_live_notifications();" title="@lang('label.notifications.title')">
            <i class="fa fa-bell" aria-hidden="true" title="@lang('label.notifications.title')"></i>
            <span class='badge badge-notification'></span>
          </a>
          <div class="dropdown-menu">
            <div class="notification-header">
              <div class="notification-title">
                <h3>@lang('label.notifications.title')</h3>
              </div>
              <div class="notification-opts">
                <a href="{{ url('notifications') }}">
                  <span class="fa fa-cogs"></span>
                </a>
              </div>
            </div>
            <div class="notification-content">
              <div class="data-notification">
                <div id="load_notifications" class="col-12 text-center">
                  <img src="{{ asset('upload/icons/spinner-small.gif') }}" alt="loading"/>
                </div>
              </div>
            </div>
          </div>
        </li>
        <li class="dropdown">
            <a class="dropdown-toggle" href="#" data-toggle="dropdown">
              <img class="u-icon img-circle" src="{{ Auth::user()->getAvatar() }}" alt="dropdown"><span class="user-name">{{ Auth::user()->name }}</span>
            </a>
            <div class="dropdown-menu" role="menu">
              <a class="dropdown-item" href="{{ url(Auth::user()->slug) }}"><i class="fa fa-user"></i> @lang('label.menu.my_profile')</a>
              <a class="dropdown-item" href="{{ url('articles') }}"><i class="fa fa-file-archive"></i> @lang('label.menu.saved_articles')</a>
              <a class="dropdown-item" href="{{ url('settings') }}"><i class="fa fa-cog"></i> @lang('label.menu.settings')</a>
              <hr/>
              {{--
              @if(Auth::user()->haveGroup())
              @php
                $gruppi = Auth::user()->getGroupsInfo();
              @endphp

                @foreach($gruppi as $value)
                  <a class="dropdown-item" href="{{ url('groups/'. $value->id) }}"><i class="fa fa-newspaper"></i> {{ $value->name }}</a>
                @endforeach

              <hr/>
              @endif
              <a class="dropdown-item" href="{{ url('group/create') }}"><i class="fa fa-users"></i> Crea gruppo</a>
              --}}
              @if(Auth::user()->isOperator())
              <a class="dropdown-item" href="{{ url('toolbox')}}" target="_blank"><i class="fa fa-toolbox"></i> Managing</a>
              @endif
              <a class="dropdown-item" href="#adiósu" onclick="document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt"></i> @lang('label.menu.logout')</a>
            </div>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">{{ csrf_field() }}</form>
          @else
            <li><a href="{{ route('login') }}">@lang('label.login')</a></li>
            <li><a href="{{ route('register') }}">@lang('label.register')</a></li>
          @endauth
      </li>
    </ul>
  </div>
</div>
