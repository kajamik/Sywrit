@extends('front.layout.app')

@section('title', $query->nome.' '.$query->cognome.' -')

@section('main')
<style>
._ou {
  cursor: pointer;
}
</style>
<div class="container">
  <div class="publisher-home">
    <section class="publisher-header" style="background-image: url({{asset($query->getBackground())}})">
      <div class="container">
        <img class="publisher-logo" src="{{asset($query->getAvatar())}}" alt="Logo">
        <div class="info">
          <span>{{$query->nome}} {{$query->cognome}}</span>
        </div>
      </div>
    </section>
    <section class="publisher-body">
      <div class="container">
        @if($query->id_gruppo == 0)
          <p>Editore individuale</p>
        @else
          <p>Lavora presso <a href="{{ url($group->slug) }}">{{$group->nome}}</a></p>
        @endif
        @if(\Auth::user() && \Auth::user()->id != $query->id)
        <div class="publisher-info">
          @if(\Auth::user()->isDirector() && !$query->haveGroup())
          <div class="_ou" id="addC">
            <a href="#" onclick="link(this.parentNode, '{{route('group/action/invite')}}')">
              <i class="fas fa-envelope"></i> <span>Assumi come collaboratore</span>
            </a>
          </div>
          @endif
          <div id="follow" class="_ou">
            @if(!$follow)
              <i class="fas fa-bell"></i> <span>Segui</span>
            @else
              <i class="fas fa-bell-slash"></i> <span>Smetti di seguire</span>
            @endif
          </div>
        </div>
        @endif
        <div class="publisher-bar" data-pub-text="#followers">
            <i class="fa fa-newspaper" title="{{$count}} Articoli"></i> <span>{{$count}}</span>
            <i class="fab fa-angellist" title="{{$query->followers_count}} Followers"></i> <span id="followers">{{$query->followers_count}}</span>
        </div>
        @if(\Auth::user() && \Auth::user()->id == $query->id)
        <ul>
          <li><a href="{{url($query->slug.'/archive')}}">Articoli Salvati</a></li>
        </ul>
        @endif
        @if(!empty($query->biography))
        <div class="py-4">
          <h4>Biografia</h4>
          <p>{!! $query->biography !!}</p>
        </div>
        @endif
        <hr/>
        <div class="publisher-content">
          <div class="py-3">
            @if($count)
            <div class="col-lg-12">
              <div class="row" id="articles">
                @foreach($articoli->take(12) as $n => $articolo)
                @php
                  if(!$n)
                    $original_image = \App\Models\BackupArticlesImages::where('article_id',$articolo->id)->first();
                @endphp
                <div class="@if($n > 0) col-lg-4 @else col-lg-8 @endif col-sm-8 col-xs-12">
                  <a href="{{ url('read/'. $articolo->slug) }}">
                    <div class="card" title="{{$n}}">
                      @if($articolo->copertina)
                        <img class="card-img-top" src="{{asset('storage/articles/'.$articolo->copertina)}}" alt="Copertina">
                      @endif
                      <div class="card-body">
                        <h4 class="card-title">{{ $articolo->titolo }}</h4>
                      </div>
                    </div>
                  </a>
                </div>
                @endforeach
              </div>
            </div>
            @else
              <p>Questo utente non ha ancora iniziato a pubblicare</p>
            @endif
          </div>
        </div>
    </div>
  </div>
</section>
  <script>
  function link(e, route){
    var el = setNode(e, {
      html: {
        "id": "__form__",
        "action": route,
        "method": "POST"
      }
    }, "form");

    setNode(el.html, {
      html: {
        "id": "_rq",
        "name": "_rq_token",
        "value": "{{$query->id}}"
      }
    }, "input");

    $("<div/>").html('{{ csrf_field() }}').appendTo($("#"+el.html.id));

    $("#"+el.html.id).submit();
  }
    App.follow('#follow',
    {
      url:'{{url("follow")}}',
      data:{'id': {{ $query->id }}, 'mode': 'i'}
      }, false);
    App.insl('articles');
  </script>
</div>
@endsection
