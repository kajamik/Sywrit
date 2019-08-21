<hr/>
@php
  $count = \DB::table('article_comments')->where('article_id', $query->id)->count();
@endphp
  <div class="feedback my-5">
    @if(Auth::guest() || (Auth::user() && !Auth::user()->suspended))
    <div class="d-flex">
      <img style="height:6em" class="p-2" src="{{ Auth::user() ? Auth::user()->getAvatar() : '' }}" />
        <div class="d-flex flex-grow-1">
          <textarea class="form-control" placeholder="{{ __('form.write_comment') }}"></textarea>
        </div>
    </div>
    <div class="py-2 col-md-12">
      <button id="sendMsg" type="button" class="btn btn-sw btn-block">
        Invia
      </button>
    </div>
    @elseif(Auth::user() && !Auth::user()->suspended)
    <div class="d-flex">
      <img style="height:6em" class="p-2" src="{{ Auth::user()->getAvatar() }}" />
        <div class="d-flex flex-grow-1 bg-sw">
          <p class="mt-3 offset-1">{{ __('label.notice.account_suspended_short') }}</p>
        </div>
    </div>
    @endif
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

      @if(Auth::user())
      $("#sendMsg").click(function(){
        updateComments();
        App.query("get","{{ url('send-comment') }}",{id: {{ $query->id }}, post: $("textarea").val() },false,function(data){
            $(data).prependTo($("#comments-data"));
        });
      });
      @else
      $(".feedback textarea").click(function(){
        validator();
      });
      @endif

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
  <button id="loadComments" class="btn btn-light mb-5">{{ __('label.social.more_comments') }}</button>
</div>
@endif
