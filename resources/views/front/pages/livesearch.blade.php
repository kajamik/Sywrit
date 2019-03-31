  <ul>
    @if($query->count())
    {{ $query }}
      {{--@foreach($query as $value)
        @if(!empty($value->titolo))
        <a href="{{ url('read/'.$value->slug) }}"><li>{{ str_limit($value->titolo, 45, '...') }}</li></a>
        @else
        <a href="{{ url($value->slug) }}"><li>{{ $value->nome }}</li></a>
        @endif
      @endforeach--}}
    @else
      <a href="{{ url('search/'.$key) }}"><li>{{ str_limit($key, 45, '...') }}</li></a>
    @endif
    <div class="py-2 pl-4">
      <a class="text-primary" href="{{ url('search/'.$key) }}">Mostra tutti i risultati</a>
    </div>
  </ul>
