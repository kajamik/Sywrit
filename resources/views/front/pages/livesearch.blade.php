  <ul>
    @if($query->count())
      <h5>{{ __('label.users_header') }}</h5>
      @foreach($query as $value)
        <a href="{{ url($value->slug) }}"><li>{{ $value->name }} {{ $value->surname }}</li></a>
      @endforeach
    @endif
    @if($query2->count())
      <h5>{{ __('label.articles_header') }}</h5>
      @foreach($query2 as $value)
        <a href="{{ url('read/'.$value->slug) }}"><li>{{ $value->titolo }}</li></a>
      @endforeach
    @endif
    {{--@if($query4->count())
      <h5>Redazioni</h5>
      @foreach($query4 as $value)
        <a href="{{ url($value->slug) }}"><li>{{ $value->name }}</li></a>
      @endforeach
    @endif--}}
    <div class="py-2 pl-4">
      <a class="text-primary" href="{{ url('search/'.$key) }}">{{ __('label.show_all_results') }}</a>
    </div>
  </ul>
