@extends('front.layout.app')

@section('titolo', $query->nome.' -')

@section('main')
<div class="publisher-home">
  <section class="publisher-header" style="background-image: url({{asset($query->getBackground())}})">
    <div class="container">
      <img class="publisher-logo" src="{{asset($query->getLogo())}}" alt="Logo">
    </div>
  </section>
  <section class="publisher-body">
    <div class="container">
      <div class="publisher-info">
        <a href="{{ url('publisher/'.$query->slug.'/create-post') }}">
          <button class="btn btn-primary"><i class="far fa-newspaper"></i> Pubblica Articolo</button>
        </a>
        @if($query->direttore == Auth::user()->id)
        <a href="{{ url('publisher/'.$query->slug.'/customize')}}">
          <button class="btn btn-primary"><i class="fas fa-cog"></i> Personalizza il sito</button>
        </a>
        @endif
      </div>
      <h1>{!! $query->nome !!} <span>({{$query->articoli->count()}} articoli)</span></h1>
      <a href="{{ url('publisher/'.$query->slug)}}">
        <i class="fas fa-home"></i> Home Page
      </a>
      <a href="{{ url('publisher/'.$query->slug.'/articles')}}">
        <i class="fas fa-newspaper"></i> Articoli
      </a>
      <a href="{{ url('publisher/'.$query->slug.'/contact')}}">
        <i class="fas fa-phone"></i> Contatti
      </a>
      <hr/>
      @include('front.pages.editoria.args.'.$slug2)
    </div>
  </section>
</div>
@endsection
