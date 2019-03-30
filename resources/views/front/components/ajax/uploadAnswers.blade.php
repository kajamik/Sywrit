  <div class="card-body">
    <div class="d-flex">
      <img style="height:4em" class="p-2" src="{{ asset(Auth::user()->getAvatar()) }}" />
        <div class="d-flex flex-grow-1">
          {{ $query->text }}
        </div>
    </div>
    <hr/>
    <div class="col-md-12">
      <span>{{ $query->created_at->diffForHumans() }}</span>
      <span><a href="{{ url(Auth::user()->slug) }}">{{ Auth::user()->nome }} {{ Auth::user()->cognome }}</a></span>
      <div class="d-inline float-right">
        <span class="fas fa-ellipsis-v"></span>
      </div>
    </div>
  </div>
