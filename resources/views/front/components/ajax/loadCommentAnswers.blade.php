@if($query->count())
<div class="card ml-1 mr-1">
@foreach($query as $value)
  <div class="card-body">
    <div class="d-flex">
      <img style="height:4em" class="p-2" src="{{ asset($value->getUserInfo->getAvatar()) }}" />
        <div class="d-flex flex-grow-1">
          {{ $value->text }}
        </div>
    </div>
    <hr/>
    <div class="col-md-12">
      <span>{{ $value->created_at->diffForHumans() }}</span>
      <span><a href="{{ url($value->getUserInfo->slug) }}">{{ $value->getUserInfo->nome }} {{ $value->getUserInfo->cognome }}</a></span>
      <div class="d-inline float-right">
        <span class="fas fa-ellipsis-v"></span>
      </div>
    </div>
  </div>
@endforeach
</div>
@endif
