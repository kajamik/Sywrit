@extends('front.layout.app')

@section('title', $query->nome.' -')

@section('main')
<div class="container">
  <div class="publisher-home">
    <div class="publisher-home">
      <section class="publisher-header" style="background-image: url({{asset($query->getBackground())}})">
        <div class="container">
          <img class="publisher-logo" src="{{asset($query->getLogo())}}" alt="Logo">
          <div class="info">
            <span>{{$query->nome}}</span>
            @if($publisher['edit'] == true)
              aaaa
            @endif
          </div>
        </div>
      </section>
      <section class="publisher-body">
        <div class="container">
          @include('front.pages.group.args.'.$slug2)
        </div>
      </section>
  <script>
    App.follow('button#follow',{url:'{{url("follow")}}',data:{'id': {{ $query->id }}, 'mode': 'g'}},false);
  </script>
  </div>
</div>
@endsection
