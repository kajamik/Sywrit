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
</style>
  @include('front.components.group.top_bar')
  <div class="publisher-content">
    <div class="py-3 container">
      <div class="row">

        <div class="offset-md-2 col-md-7 col-12">
          {{-- inizia a conversare --}}
          @if((Auth::check() && Auth::user()->hasMemberOf($query->id)) || $query->public)

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
                                <textarea class="form-control" placeholder="Inizia a conversare"></textarea>
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

                  <hr/>

                  <div id="conversations" class="py-3 col-md-12">

                    <script>
                    var q = 1;

                    $(function(){
                      updateConversations();
                    });

                    $("button").click(function(){
                      updateConversations();
                      App.query("get","{{ url('ajax/groups/sendMessage') }}",{ id: {{ $query->id }}, post: $("textarea").val() },false,function(data){
                          $(data).prependTo($("#conversations"));
                      });
                    });

                    function updateConversations() {
                      App.query("get","{{ url('ajax/groups/loadMessages') }}",{ id: {{ $query->id }}, q: this.q },false,function(data){
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

                    </script>

                  </div>
                </div>

            <div class="col-lg-3">

              <div class="sw-component">
                <div class="sw-component-header bg-sw">Membri</div>
                <div class="sw-item p-3">
                  @foreach($query->getMembers(5) as $value)
                  <a href="{{ url($value->slug) }}">
                    <img class="u-icon img-circle" src="{{ $value->avatar }}" title="{{ $value->name }}" alt="">
                  </a>
                  @endforeach
                </div>
                <div class="sw-component-header bg-sw">Descrizione</div>
                <div class="sw-item text-center">
                  {!! $query->description !!}
                </div>
              </div>

            </div>

            @else
            <p>Questo gruppo è privato.</p>
            @endif
          </div>
        </div>
      </div>

    {{-- close top_bar --}}
  </div>
  </div>
</div>
@endsection
