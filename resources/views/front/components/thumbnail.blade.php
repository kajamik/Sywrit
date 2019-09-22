<div class="p-2 row">
  <img style="height:4em" class="p-2" src="{{ $query->getAvatar() }}" />
  <div class="col-md-9 col-9">
    <h5><a href="#">
    @if($type == "group")
      {!! $query->name !!}
    @elseif($type == "profile")
      {!! $query->getRealName() !!}
    @endif
    </a></h5>
    <div class="mt-2">
    @if(isset($query->biography))
      <span>{!! $query->biography !!}</span>
    @endif
    </div>
    <div class="mt-2">
    <button class="btn btn-sw btn-block"><i class="fa fa-plus"></i> Segui</button>
    </div>
  </div>
</div>
