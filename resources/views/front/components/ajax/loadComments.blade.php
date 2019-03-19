@foreach($query as $value)
<div class="card">
  <div class="card-header bg-dark">
    <a href="{{ url($value->getUserInfo->slug) }}" class="text-light">{{ $value->getUserInfo->nome }} {{ $value->getUserInfo->cognome }}</a>
    <div class="float-right text-light">
      <span>{{ $value->created_at->diffForHumans() }}</span>
    </div>
  </div>
  <div class="card-body">
    <div class="d-flex">
      <img style="height:4em" class="p-2" src="{{ asset($value->getUserInfo->getAvatar()) }}" />
        <div class="d-flex flex-grow-1">
          {{ $value->text }}
        </div>
    </div>
  </div>

  {{-- Risposte--}}

</div>
@endforeach
