<div class="publisher-home">
  <div class="publisher-header" style="background-image: url({{ $query->getBackground() }})">
    <div class="container">
      <div class="publisher-logo">
        <div class="row">
          <div class="d-inline">
            <img class="img-medium" src="{{ $query->getAvatar() }}" alt="">
          </div>
          <div class="ml-2 mt-2 info">
            <span>
            @if($type == "group")
              {!! $query->name !!}
            @elseif($type == "profile")
              {!! $query->getRealName() !!}
            @endif
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
  {{-- <div class="card">
    <div class="card-header">Ultimo articolo</div>
    <div class="card-body">
      <p>scritto 1</p>
    </div>
  </div> --}}
</div>
