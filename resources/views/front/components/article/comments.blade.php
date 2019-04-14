<hr/>
@php
  $count = \DB::table('article_comments')->where('article_id', $query->id)->count();
@endphp
  <div class="feedback my-5">
    @auth
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
      <img style="height:6em" class="p-2" src="{{ asset('upload/default.png') }}" />
        <div class="d-flex flex-grow-1 bg-light">
          <p class="mt-3 offset-1">Per commentare devi prima effettuare l'accesso</p>
        </div>
    </div>
    @endif
  </div>

  {{-- Commenti--}}

  <div id="comments-data" class="py-3 col-md-12">

    <script>
    var instance = false;
    var count = 0;

      updateComments();

      $("#sendMsg").click(function(){
        updateComments();
        App.query("get","{{ url('send-comment') }}",{id: {{ $query->id }}, post: $("textarea").val() },false,function(data){
            $(data).prependTo($("#comments-data"));
        });
      });

    function getState() {
      App.query("get","{{ url('getStateComments') }}",{id: {{ $query->id }}, count: count},false,function(data) {
          //updateComments();
      });
    }

    function updateComments(query = 1) {
      App.query("get","{{ url('load-comments') }}",{id: {{ $query->id }}, q: query },false,function(data){
        $($("#comments-data")).append(data);
      });
    }

    </script>

  </div>
{{-- End --}}

@if($count > 6)
<div class="offset-md-5">
  <button onclick="updateComments()" class="btn btn-light pb-2">Carica altri commenti</button>
</div>
@endif
