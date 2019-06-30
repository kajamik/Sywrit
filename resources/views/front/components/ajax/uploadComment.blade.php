<div class="card">
  <div class="card-header bg-sw">
    <a href="#" class="text-light">{!! Auth::user()->getRealName() !!}</a>
    <div class="float-right text-light">
      {{ $post->created_at->diffForHumans() }}
    </div>
  </div>
  <div class="card-body">
    <div class="d-flex">
      <img style="height:4em" class="p-2" src="{{ Auth::user()->getAvatar() }}" />
        <div class="d-flex flex-grow-1">
          {{ $post->text }}
        </div>
    </div>
  </div>
</div>
