@extends('front.layout.app')

@section('main')

<style type="text/css">
#nav > li {
  display: inline-block;
  margin-top: 5px;
  margin-bottom: 10px;
  font-size: 20px;
}
#nav > li:not(:last-child)::after {
  content: '\00a0|';
}
.message {
  margin-bottom: 20px;
  padding: 26px;
  border: .5px solid #eee;
}
.new-notification {
  background-color: #eee;
}
.message-title {
  padding: 5px 15px;
  border-bottom: 1px solid #bfb8eb;
}
.message-body {
  padding: 5px 16px;
  font-size: 23px;
}
.message-body a {
  color: #aaa;
}
.message-response {
  margin-top: 5px;
  margin-left: 50px;
}
.message-close {
  font-size: 20px;
  cursor: pointer;
}
</style>

  <div class="publisher-home">
    <div class="publisher-header" style="background-image: url({{ Auth::user()->getBackground() }})">
      <div class="container">
        <div class="publisher-logo">
          <div class="row">
            <div class="d-inline">
              <img src="{{ Auth::user()->getAvatar() }}" alt="Logo">
            </div>
            <div class="ml-2 mt-2 info">
              <span>
                {!! Auth::user()->getRealName() !!}
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
    <nav class="publisher-nav">
      <ul id="nav">
        <li><a href="{{ url(Auth::user()->slug) }}">@lang('label.a_profile')</a></li>
        <li><a href="{{ url(Auth::user()->slug.'/about') }}">@lang('label.a_contact')</a></li>
        <li><a href="{{ url(Auth::user()->slug.'/archive') }}">@lang('label.saved_articles')</a></li>
      </ul>
    </nav>
      <div class="publisher-body">
        <hr/>
        <h2>@lang('label.notifications_header')</h2>
        @if($query->count())
        <a href="#" onclick="delAll()">@lang('label.delete_all ')</a>
        <div class="notifications">
          @foreach($query as $value)
          @php
            if($value->marked == '0'){
              $value->marked = '1';
              $value->save();
            }
            @endphp
            @if($value->type == '1') {{-- Collaborazione --}}
            @php
              $publisher_request = \DB::table('publisher_request')->find($value->content_id);
              $publisher = \DB::table('editori')->find($publisher_request->publisher_id);
            @endphp
            <div id="noty_{{ $value->id }}" class="message">
              <div class="message-content">
                <div class="message-close float-right" id="close">&times;</div>
                <div class="message-title">
                  <time>{{ $value->created_at->diffForHumans() }}</time>
                </div>
                <div class="message-body my-3 pl-3">
                  @lang('label.new_request_group', ['group' => $publisher->name])
                  <div class="actions">
                    <button id="acceptRequest_{{ $value->id }}" class="btn btn-primary" type="role">
                      @lang('form.accept')
                    </button>
                    <script>
                    $("#acceptRequest_{{ $value->id }}").click(function() {
                      App.query('get','{{ url("request_accepted") }}', {id: {{ $value->id }}}, false, function(data){
                        $("#noty_{{ $value->id }}").html("<h5>Richiesta Accettata</h5>");
                        $("#noty_{{ $value->id }}").fadeOut(2000);
                      });
                    });
                  </script>
                  </div>
                </div>
              </div>
            </div>
            @elseif($value->type == '2') {{-- Rating --}}
            @php
              $articolo = \App\Models\Articoli::where('id', $value->content_id)->first();
            @endphp
            <div id="noty_{{ $value->id }}" class="message">
              <div class="message-content">
                <div class="message-close float-right">&times;</div>
                <div class="message-title">
                  <time>{{ $value->created_at->diffForHumans() }}</time>
                </div>
                <div class="message-body">
                  <p>
                    @lang('label.artice_new_likes', ['name' => $articolo->titolo])
                  </p>
                </div>
              </div>
            </div>
            @elseif($value->type == '3') {{-- Comment --}}
            @php
              $articolo = \App\Models\Articoli::where('id', $value->content_id)->first();
            @endphp
            <div id="noty_{{ $value->id }}" class="message">
              <div class="message-content">
                <div class="message-close float-right">&times;</div>
                <div class="message-title">
                  <time>{{ $value->created_at->diffForHumans() }}</time>
                </div>
                <div class="message-body">
                  <p>
                    @lang('label.article_new_comment', ['user' => $value->getUserName->name. ' '.$value->getUserName->name, 'name' => $articolo->titolo])
                  </p>
                </div>
              </div>
            </div>
            @endif
            <script>
            $("#noty_{{ $value->id }} .message-close").click(function(){
              App.query('get','{{ url("notification_delete") }}', { id: {{ $value->id }} }, false, function(data){
                  $("#noty_{{ $value->id }}").fadeOut();
              });
            });
            </script>
            @endforeach
          </div>
          {{ $query->links() }}
          @else
          <p>@lang('label.notifications_no_content')</p>
          @endif
        </div>
    </div>
@if($query->count())
<script>
function delAll() {
  App.query('get','{{ url("notifications_delete") }}', null, false, function(data){
      $(".notifications *").fadeOut(function(){
        $(".notifications").html("<h5>@lang('label.notifications_no_content')</h5>");
      });
  });
}
</script>
@endif
@endsection
