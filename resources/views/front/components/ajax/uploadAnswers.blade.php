  <div class="card-body">
    <div class="d-flex">
      <img style="height:4em" class="p-2" src="{{ Auth::user()->getAvatar() }}" />
        <div class="d-flex flex-grow-1">
          {{ $post->text }}
        </div>
    </div>
    <hr/>
    <div class="col-md-12">
      <span>{{ $post->created_at->diffForHumans() }}</span>
      <span><a href="{{ url(Auth::user()->slug) }}">{!! Auth::user()->getRealName() !!}</a></span>
    </div>
  </div>
