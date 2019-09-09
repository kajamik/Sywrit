<div class="card">
  <div class="card-body">
    <div class="d-flex">
      <div class="row">
        <img style="height:4em" class="p-2" src="{{ Auth::user()->getAvatar() }}" />
        <div class="col-9">
          <h4><a href="{{ Auth::user()->slug }}">{!! Auth::user()->getRealName() !!}</a></h4>
          <span>{{ $post->created_at->diffForHumans() }} {{-- $post->permission->tag() --}}</span>
          <hr/>
        </div>
        <div class="ml-3 d-flex col-12">
          {{ $post->text }}
        </div>
      </div>
    </div>
  </div>
</div>
