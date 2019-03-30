<div class="card">
  <div class="card-header bg-dark">
    <a href="#" class="text-light">{{ $query->getUserInfo->nome }} {{ $query->getUserInfo->cognome }}</a>
    <div class="float-right text-light">
      <span>{{ $query->created_at->diffForHumans() }}</span>
    </div>
  </div>
  <div class="card-body">
    <div class="d-flex">
      <img style="height:4em" class="p-2" src="{{ asset($query->getUserInfo->getAvatar()) }}" />
        <div class="d-flex flex-grow-1">
          {{ $query->text }}
        </div>
    </div>
  </div>
</div>
