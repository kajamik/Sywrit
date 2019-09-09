@if($node)
<div class="mt-3 col-12">
<a href="{{ $node['url'] }}" target="_blank">
  <div class="card">
    <img class="card-img-top" src="{{ $node['image'] }}" />
      <div class="card-body">
        <p>
          {{ $node['domain'] }}
          {{-- ucfirst(explode('.', preg_split('/[a-z:]*\/\/[ww*.*]*|\/(.*)/', $url)[1])[0]) --}}
          @isset($node['author'])
            | Di {{ $node['author'] }}
          @endisset
        </p>
        <h5 class="card-title">{{ $node['title'] }}</h5>
      </div>
  </div>
</a>
</div>
@else
<p>Non è possibile trovare il sito</p>
@endif
