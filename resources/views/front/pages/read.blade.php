@extends('front.layout.app')

@section('title', $query->titolo.' -')

@php
  $autore = \App\Models\User::find($query->autore);
  if($query->id_gruppo > 0)
    $editore = \App\Models\Editori::find($query->id_gruppo);
@endphp

@section('main')
<style>
.publisher-body .block-article a, .publisher-body .block-article a:hover {
  text-decoration: underline;
}
div.date-info {
  margin-top: 12px;
}
span.date {
  text-transform: capitalize;
}
span.time {
  padding: 0;
}
.block-body {
  padding: 12px;
}
.block-body p {

}
.block-footer > .feeds {
  margin-top: 15px;
  font-size: 33px;
}
</style>
<div class="container">
  <div class="publisher-home">
    @if(!empty($editore))
    <section class="publisher-header" style="background-image: url({{asset($editore->getBackground())}})">
      <div class="container">
        <img class="publisher-logo" src="{{asset($editore->getLogo())}}" alt="Logo">
        <div class="info">
          <span>{{$editore->nome}}</span>
        </div>
      </div>
    </section>
    @else
    <section class="publisher-header" style="background-image: url({{asset($autore->getBackground())}})">
      <div class="container">
        <img class="publisher-logo" src="{{asset($autore->getAvatar())}}" alt="Logo">
        <div class="info">
          <span>{{$autore->nome}} {{$autore->cognome}}</span>
        </div>
      </div>
    </section>
    @endif
    <section class="publisher-body">
      @if(!$query->status)
        @if($query->id_gruppo > 0)
          @include('front.components.article.group_tools')
          @if(!$query->status)
            Articolo non pubblicato
          @elseif($query->status == 1)
            Articolo in attesa di revisione
          @endif
        @else
          @include('front.components.article.my_tools')
        @endif
      @endif
      <article class="block-article">
        <div class="block-title">
          <h1>{{ $query->titolo }}</h1>
        </div>
        <div>
          Scritto da <a href="{{url($autore->slug)}}">{{ $autore->nome }} {{ $autore->cognome }}</a>
          <div class="date-info">
            <span class="date"><i class="far fa-calendar-alt"></i> {{ $date }}</span>
            <span class="time"><i class="far fa-clock"></i> {{ $time }}</span><br/>
          </div>
        </div>
        <hr/>
        <div class="block-body">
          <img src="{{asset($query->getBackground())}}" style="max-height:230px;" alt="copertina">
          <p>
            {!! $query->testo !!}
          </p>
        </div>
        @if(!empty($query->tags))
        <div class="block-meta">
          <ul class="meta-tags">
            <i class="fa fa-tags"></i>
            @foreach($tags as $tag)
              <li>#{{$tag}}</li>
            @endforeach
          </ul>
        </div>
        @endif
      <hr/>
      <div class="block-footer">
        <div class="socials">
          <p>{{$query->likes}} <i class="fa fa-thumbs-up"></i> Mi piace</p>
          <p>{{$query->views_count}} <i class="far fa-eye"></i> Visualizzazioni</p>
          <p><i class="fa fa-share-alt"></i> Condividi</p>
          <p><i class="far fa-flag"></i> Segnala</p>
        </div>
        @if(!empty($query2) && \Auth::user())
        <h5>Seguici per tenerti aggiornato sulle ultime notizie</h5>
        <button id="follow" class="btn btn-info">
          @if(!$follow)
          <i class="fas fa-bell"></i> <span>Segui</span>
          @else
          <i class="fas fa-bell-slash"></i> <span>Smetti di seguire</span>
          @endif
        </button>
        @endif
        <div class="feeds">
          articoli simili
        </div>
      </div>
    </article>
  </section>
  @auth
  @if(!empty($query2))
  <script>
    App.follow('#follow',{url:'{{url("follow")}}',data:{'id': {{ $editore->id }}, 'mode': 'g'}},false);
  </script>
  @endif
  @endauth
  </div>
</div>
@endsection
