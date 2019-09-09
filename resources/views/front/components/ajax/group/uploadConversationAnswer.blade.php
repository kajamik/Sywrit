<div class="card-body">
  <div class="row">
    <img style="height:4em" class="p-2" src="{{ Auth::user()->getAvatar() }}" />
      <div class="col-md-9 col-9">
        <h4><a class="thumbnail" href="{{ url(Auth::user()->slug) }}" data-card-url="/ajax/thumbnail/?id={{ Auth::user()->id }}&h=profile"> {!! Auth::user()->getRealName() !!}</a></h4>
        <div class="mt-2">
          {{ $post->text }}
        </div>
    </div>
  </div>
<hr/>
<div class="col-md-12">
  {{ $post->created_at->diffForHumans() }}
</div>
</div>
