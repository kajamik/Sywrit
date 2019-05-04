<div class="card">
  <div class="card-header bg-dark">
    <a href="#" class="text-light">{{ Auth::user()->name }} {{ Auth::user()->surname }}</a>
    <div class="float-right text-light">
      <span>{{ $post->created_at->diffForHumans() }}</span>
    </div>
  </div>
  <div class="card-body">
    <div class="d-flex">
      <img style="height:4em" class="p-2" src="{{ asset(Auth::user()->getAvatar()) }}" />
        <div class="d-flex flex-grow-1">
          {{ $post->text }}
        </div>
    </div>
  </div>
</div>
