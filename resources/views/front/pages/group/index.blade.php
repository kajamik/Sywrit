@extends('front.layout.app')

@section('main')
<style>
.tab li {
  padding: 6px;
  display: inline-block;
}
.tab li.tab-active a {
  color: #fff;
}
.tab-font-md li {
  font-size: 18px;
}
.dialog_box {
  width: 100%;
  height: 100%;
  border: 1px solid #000;
  border-radius: 4px;
}
.dialog_editor {
  height: 100%;
  min-height: 40px;
  cursor: text;
}
.dialog_editor__editable:empty:before {
    content: attr(data-text);
    color: gray;
}
.dialog_editor__editable {
  width: 100%;
  height: 100%;
  word-break: break-all;
  color: #000;
  font-size: 16px;
}
.dialog_editor__editable:focus {
  outline: none;
  min-height: 70px;
}
.dialog_editor__preview_close {
  position: absolute;
  right: 60px;
  bottom: 120px;
  font-size: 28px;
  z-index: 1;
  cursor: pointer;
}
</style>
  @include('front.components.group.top_bar')
  <div class="publisher-content">
    <div class="py-3">
      <div class="row">

        <div class="offset-md-2 col-md-7 col-12">
          {{-- inizia a conversare --}}
          @if(Auth::user() && Auth::user()->hasMemberOf($query->id))
                @if(Auth::user()->groupInfo->hasStaff() && $query->requests() > 0)
                  <p><a href="{{ url('groups/'. $query->id. '/admin/requests') }}">Ci sono ({{ $query->requests() }}) nuove richieste di iscrizione.</a></p>
                @endif
                  <div class="col-md-12">
                    <ul class="tab tab-font-md bg-sw">
                      <li class="tab-active" tab="#state"><a href="#">Conversazione</a></li>
                      <li tab="#article"><a href="#">Articolo</a></li>
                    </ul>
                    <div class="tab-content">
                      <div id="state">
                        <form method="POST">
                            @csrf
                            <div class="form-group row">
                              <div class="col-md-12">
                                <div class="dialog_box">
                                  <div class="dialog_editor form-control">
                                    <div class="dialog_editor__editable" contenteditable="true" data-text="Inizia a conversare"></div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="form-group row mb-0">
                                <div class="col-md-12">
                                    <button type="button" class="btn btn-sw btn-block">
                                        Pubblica
                                    </button>
                                </div>
                            </div>
                        </form>
                      </div>
                      <div id="article">
                        <a href="{{ url('groups/'. $query->id .'/write') }}">
                          <button type="button" class="form-control">
                           Crea un nuovo articolo
                          </button>
                        </a>
                      </div>
                    </div>
                  </div>

              @else
              <button type="button" class="btn btn-sw btn-block">
                <i class="fa fa-plus"></i> Iscriviti al gruppo
              </button>
              @endif

              <hr/>
                <div id="conversations" class="py-3 col-md-12"></div>
                    <script>
                    var q = 1;

                    $(function(){
                      updateConversations();
                    });

                    @if(Auth::user())
                      @if(Auth::user()->hasMemberOf($query->id))
                        $("button").click(function() {
                          $.get("{{ url('ajax/groups/sendMessage') }}", { id: {{ $query->id }}, post: $("textarea").val(), reply: 0 }, function(data) {
                              $("textarea").val('');
                              $(data).prependTo($("#conversations"));
                          });
                        });
                      @else
                      $("button").click(function() {
                        App.query("get","{{ url('ajax/groups/joinRequest') }}", { id: {{ $query->id }} },false,function(data) {
                          App.getUserInterface({
                            "ui": {
                              "title": "Richiesta inviata",
                              "content": [
                                {"type": ["h5"], "text": "Hai inviato la richiesta agli amministratori del gruppo."}
                              ]
                            }
                          });
                        });
                      });
                      @endif
                    @else
                    $("button").click(function() {
                      $.get("{{ url('ajax/auth') }}", {path: '{{ Request::path() }}', callback: 'auth_login'}, function(data) {
                        App.getUserInterface({
                          "ui": {
                            "title": "Benvenuto ospite!!",
                            "content": data
                          }
                        }, true);
                      });
                    });
                    @endif

                    function updateConversations() {
                      $.get("{{ url('ajax/groups/loadMessages') }}",{ id: {{ $query->id }}, q: this.q, answer: 0 }, function(data) {
                        if(data) {
                          $("#conversations").append(data);
                          q++;
                        } else {
                          $("#loadComments").remove();
                        }
                      });
                    }

                    // TAB

                    $(".tab > li").each(function(i, items) {
                      var tab = $(items).attr("tab");
                      if($(items).attr("class") != "tab-active") {
                        $(".tab-content > div" + tab).css('display', 'none');
                      }
                    });
                    $(".tab").on('click', 'li', function() {
                      if($(this).attr('class') != "tab-active") {
                        $('.tab > li').removeClass('tab-active');
                        $(this).addClass('tab-active');
                        $(".tab-content > div").css('display', 'none');
                        $(".tab-content > div" + $(this).attr("tab")).css('display', 'block');
                      }
                      return false;
                    });

                    $(".dialog_editor__editable").on('input', function() {
                      var $this = $(this);
                      var $text = $(this).text();
                      var $exp = new RegExp('[a-z:]*\/\/[ww*.*]*|\/(.*)');
                      if($text.match($exp) && $(".dialog_preview").length == 0) {
                        $.get("{{ url('ajax/info') }}", {url: $text}, function(data) {
                          $this.parent().after("<div class='dialog_preview'><hr/><div class='dialog_preview_close'>&times;</div>"+ data +"</div>");

                          $(".dialog_preview_close").on('click', function() {
                            $(".dialog_preview").remove();
                          });
                        });
                      }

                    });
                    </script>
                </div>

            <div class="col-lg-3">
              <div class="sw-component">
                {{--<div class="sw-component-header bg-sw">Amministratori</div>
                <div class="sw-item p-3">
                  @foreach($query->getAdministrators(5) as $value)
                  <a class="thumbnail" href="{{ url($value->slug) }}" data-card-url="/ajax/thumbnail/?id={{ $value->id }}&h=profile">
                    <img class="u-icon img-circle" src="{{ $value->avatar }}">
                  </a>
                  @endforeach
                </div>--}}
                <a href="{{ url('groups/'. $query->id. '/members') }}">
                  <div class="sw-component-header bg-sw">({{ $query->getMembers()->count() }}) Membri</div>
                </a>
                <div class="sw-item p-3">
                  @foreach($query->getMembers(5) as $value)
                  <a class="thumbnail" href="{{ url($value->slug) }}" data-card-url="/ajax/thumbnail/?id={{ $value->id }}&h=profile">
                    <img class="u-icon img-circle" src="{{ $value->avatar }}">
                  </a>
                  @endforeach
                </div>
                <div class="sw-component-header bg-sw">Descrizione</div>
                <div class="sw-item text-center">
                  {!! $query->description !!}
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>

    {{-- close top_bar --}}
  </div>
  </div>
</div>
@endsection
