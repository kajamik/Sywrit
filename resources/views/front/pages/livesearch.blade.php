  <ul>
    @if(!$users->count() && !$articles->count() && !$pages->count())
        <li>Nessuna corrispondenza trovata</li>
    @endif
    @if($users->count())
      @foreach($users as $value)
        <a href="{{ url($value->slug) }}"><li>{{ $value->nome }} {{ $value->cognome }}</li></a>
      @endforeach
    @endif
    @if($articles->count())
      @foreach($articles as $value)
        <a href="{{ url('read/'.$value->slug) }}"><li>{{ $value->titolo }}</li></a>
      @endforeach
    @endif
    @if($pages->count())
      @foreach($pages as $value)
        <a href="{{ url($value->slug) }}"><li>{{ $value->nome }}</li></a>
      @endforeach
    @endif
  </ul>
