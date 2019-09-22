@foreach($query->getMembers(5 * ($current_page - 1), 5) as $value)
  <div class="mt-2">
    <div class="row">
      <img class="img-medium img-circle" src="{{ $value->avatar }}" alt="{{ $value->name }} {{ $value->surname }}">
      <div class="col-md-9 col-8">
        <h4><a class="thumbnail" href="{{ url($value->slug) }}" data-card-url="/ajax/thumbnail/?id={{ $value->id }}&h=profile">{{ $value->name }} {{ $value->surname }}</a></h4>
        <medium>Utente</medium>
      </div>
    </div>
  </div>
  @endforeach
