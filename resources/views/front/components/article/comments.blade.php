<hr/>
@php
  $count = \DB::table('article_comments')->where('article_id', $query->id)->count();
@endphp
  <div class="feedback my-5">
    @auth
    @if(Auth::user() && !Auth::user()->suspended)
    <div class="d-flex">
      <img style="height:6em" class="p-2" src="{{ asset(Auth::user()->getAvatar()) }}" />
        <div class="d-flex flex-grow-1">
          <textarea class="form-control" placeholder="Scrivi un commento..."></textarea>
        </div>
    </div>
    <div class="py-2 col-md-12">
      <button id="sendMsg" type="button" class="btn btn-dark btn-block">
        Invia
      </button>
    </div>
    @else
    <div class="d-flex">
      <img style="height:6em" class="p-2" src="{{ asset(Auth::user()->getAvatar()) }}" />
        <div class="d-flex flex-grow-1 bg-light">
          <p class="mt-3 offset-1">Questo account è stato sospeso da un operatore</p>
        </div>
    </div>
    @endif
    @else
    <div class="d-flex">
      <img style="height:6em" class="p-2" src="{{ asset('upload/default.png') }}" />
        <div class="d-flex flex-grow-1 bg-light">
          <p class="mt-3 offset-1">Per commentare devi prima effettuare l'accesso</p>
        </div>
    </div>
    @endauth
  </div>

  {{-- Commenti--}}

  <div id="comments-data" class="py-3 col-md-12">

    <script>
    var q = 1;
    var _qa = new Array();

    $(function(){
      updateComments();
      $("#loadComments").click(function(){
        updateComments();
      });
    });

      $("#sendMsg").click(function(){
        updateComments();
        App.query("get","{{ url('send-comment') }}",{id: {{ $query->id }}, post: $("textarea").val() },false,function(data){
            $(data).prependTo($("#comments-data"));
        });
      });

    function updateComments() {
      App.query("get","{{ url('load-comments') }}",{id: {{ $query->id }}, q: this.q },false,function(data){
        if(data) {
          $("#comments-data").append(data);
          q++;
        } else {
          $("#loadComments").remove();
        }
      });
    }

    </script>

  </div>
{{-- End --}}

@if($count > 6)
<div class="offset-md-5">
  <button id="loadComments" class="btn btn-light pb-2">Carica altri commenti</button>
</div>
@endif
