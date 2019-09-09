@extends('front.layout.app')

@section('main')
<style>
.tab li {
  padding: 6px;
  display: inline-block;
}
.tab li.tab-active a {
  color: #fff;
}
.tab-font-md li {
  font-size: 18px;
}
</style>
  @include('front.components.group.top_bar')
  <div class="publisher-content">
    <div class="py-3">
      @if($requests->count() > 0)
      <div class="card">
        <div class="card-body">
          @foreach($requests as $value)
          <div class="mt-2" data-form="{{ $value->id }}">
            <div class="row">
              <img class="img-medium img-circle" src="{{ $value->avatar }}" alt="{{ $value->name }} {{ $value->surname }}">
              <div class="col-md-9 col-8">
                <h4><a class="thumbnail" href="{{ url($value->slug) }}" data-card-url="/ajax/thumbnail/?id={{ $value->id }}&h=profile">{{ $value->name }} {{ $value->surname }}</a></h4>
                <medium><button class="btn" type="button" data-send='{"id": {{ $value->request_id }}, "res": 1}'>Accetta</a></button>
                <medium><button class="btn" type="button" data-send='{"id": {{ $value->request_id }}, "res": 0}'>Rifiuta</a></button>
              </div>
            </div>
          </div>
          @endforeach
        </div>
      </div>
      @else
      <p>Non ci sono richieste di iscrizione</p>
      @endif
    </div>
  </div>

  {{-- close top_bar --}}
</div>
</div>
</div>

@endsection

@section('js')
<script>
$('button[data-send]').on('click', function() {
  var a = JSON.parse(JSON.stringify($(this).data('send')));
  $.get('{{ url("ajax/groups/joinResponse") }}', a, function(data) {
    $("div[data-form="+ data.id +"]").hide();
  });
});
</script>
@endsection
