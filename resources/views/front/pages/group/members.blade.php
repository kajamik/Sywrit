@extends('front.layout.app')

@section('main')
  @include('front.components.group.top_bar')
  <div class="publisher-content">
    <div class="py-3 container">
      <h3>({{ $query->getMembers()->count() }}) Membri</h3>
      <div class="card">
        <div id="members" class="card-body">
        @foreach($query->getMembers(0, 5) as $value)
          <div class="mt-2">
            <div class="row">
              <img class="img-medium img-circle" src="{{ $value->avatar }}" alt="{{ $value->name }} {{ $value->surname }}">
              <div class="col-md-9 col-8">
                <h4><a class="thumbnail" href="{{ url($value->slug) }}" data-card-url="/ajax/thumbnail/?id={{ $value->id }}&h=profile">{{ $value->name }} {{ $value->surname }}</a></h4>
              </div>
            </div>
          </div>
          @endforeach
        </div>
      </div>
      <div class="p-2">
        <button id="more" class="btn btn-sw btn-block">
          Mostra altro
        </button>
      </div>
    </div>
  </div>

    {{-- close top_bar --}}
  </div>
  </div>
</div>
@endsection

@section('js')
<script>
var q = 2;
$(document).on('click', '#more', function() {
  $.get("{{ url('ajax/groups/loadMembers')}}", {id: {{ $query->id }}, q: q}, function(data) {
    q++;
    $("#members").append(data);
  });
});
</script>
@endsection
