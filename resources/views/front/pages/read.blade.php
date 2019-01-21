@extends('front.layout.app')

@section('title', $query->titolo.' -')

@php
  $autore = \App\Models\User::find($query->autore);
  $editore = \App\Models\Editori::find($query->id_gruppo);
@endphp

@section('main')
<div class="container">
  <article class="block-article">
    <div class="block-title">
      <h1>{{ $query->titolo }}</h1>
    </div>
    <div>
      Scritto da <a href="{{url('profile/'.$autore->slug)}}">{{ $autore->nome }}</a> Lunedì, 1 gennaio alle ore 00:00<br/>
      @if($editore)
        Redazione <a href="{{url('group/'.$editore->slug)}}">{{ $editore->nome }}</a>
      @endif
    </div>
    <hr/>
      <div class="block-body">
        <p>{!! $query->testo !!}</p>
      </div>
      <div class="block-meta">
        aaa
      </div>
    <hr/>
    <div class="block-footer">
      <div class="socials">
        <p>{{$query->piaciuto}} <i class="far fa-thumbs-up"></i> Mi piace</p>
        <p><i class="fa fa-share-alt"></i> Condividi</p>
      </div>
      @if(!$editore->hasMember())
      <h5>Seguici per tenerti aggiornato sulle ultime notizie</h5>
        @if(!$follow)
        <button id="follow" class="btn btn-primary"><i class="fas fa-bell"></i> <span>Segui</span></button>
        @else
        <button id="follow" class="btn btn-primary"><i class="fas fa-bell-slash"></i> <span>Smetti di seguire</span></button>
        @endif
      @endif
    </div>
  </article>
  <script>
    App.follow('button#follow',{url:'{{url("follow")}}',data:{'id': {{ $editore->id }}, 'mode': 'g'}},false);
  </script>
</div>
@endsection
