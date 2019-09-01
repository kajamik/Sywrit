@extends('front.layout.app')

@section('main')
  @include('front.components.group.top_bar')
  <div class="publisher-content">
    <div class="py-3 container">
      <h3>({{ $query->getMembers()->count() }}) Membri</h3>
      <div class="card">
        <div class="card-body">
        @foreach($query->getMembers(5) as $value)
          <div class="mt-2">
            <div class="row">
              <img class="img-medium img-circle" src="{{ $value->avatar }}" alt="{{ $value->name }} {{ $value->surname }}">
              <div class="col-md-9 col-12">
                <h4><a class="thumbnail" href="{{ url($value->slug) }}" data-card-url="/thumbnail/?id={{ $value->id }}&h=profile">{{ $value->name }} {{ $value->surname }}</a></h4>
                <medium>Utente</medium>
              </div>
            </div>
          </div>
          @endforeach
          <div class="p-2">
            <button class="btn btn-sw btn-block">
              Mostra altro
            </button>
          </div>
        </div>
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
$("a.thumbnail").on('mouseenter', function() {
    var $this = $(this);
    $.get($this.data("card-url"), function(data) {
      $this.after("<div class='info-box'>"+data+"</div>");
    });
});
$("*").on('mouseenter', function() {
    $(".info-box").remove();
});
</script>
@endsection
