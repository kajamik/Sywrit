@extends('front.layout.app')

@section('title', $query->titolo. ' - ')

@php
  $autore = \App\Models\User::find($query->id_autore);
  if($query->id_gruppo > 0) {
    $editore = \App\Models\Editori::find($query->id_gruppo);
    $collection = collect(explode(',',$editore->followers));
  } else {
    $collection = collect(explode(',',$autore->followers));
  }

  if(Auth::user() && $collection->some(\Auth::user()->id)){
    $follow = true;
  }else{
    $follow = false;
  }

  $rating = collect(explode(',',$query->rated));
  if(Auth::user() && $rating->some(Auth::user()->id)) {
    $hasRate = true;
  } else {
    $hasRate = false;
  }
@endphp

@section('seo')

    <meta property="og:title" content="{!! $query->titolo !!} - {{ config('app.name') }}" />
    <meta property="og:description" content="{!! str_limit(strip_tags($query->testo), 40, '...') !!}" />
    <meta property="og:type" content="article" />
    <meta property="og:url" content="{{ Request::url() }}" />
    <meta property="og:image" content="{{ asset($query->getBackground()) }}" />
    <meta property="article:published_time" content="{{ $query->created_at }}" />
    <meta property="article:author" content="{{ $autore->name }} {{ $autore->surname }}" />
    <meta property="article:tag" content="{{ $query->tags }}" />
@endsection

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
.feeds {
  padding: 15px;
}
.btn-custom {
  background-color: #fff;
  border: 1px solid #000;
  border-radius: 3px;
}
.btn-custom:active {
  outline: none;
}
._button_active_ {
  background-color: #A22932;
  color: #fff;
}
</style>
  <div class="publisher-home">
    <div class="publisher-body">
      <a href="{{ url('read/'.$query->slug) }}" class="text-underline">{{ trans('< Indietro') }}</a>
        @auth
        <div class="publisher-info">
          @if(Auth::user() && $query->id_autore != \Auth::user()->id)
          <div class="col-md-12">
            <button id="follow" class="btn-custom">
              @if($follow)
              <span id="follow_icon" class="fas fa-bell"></span>
              <strong id="follow_text">{{ trans('Smetti di seguire') }}</strong>
              @else
              <span id="follow_icon" class="far fa-bell"></span>
              <strong id="follow_text">{{ trans('Inizia a seguire') }}</strong>
              @endif
            </button>
          </div>
          <script>
          document.getElementById("follow").onclick = function(){
            App.query('GET','{{ url("follow?q=true") }}', {id: '{{ $query->id_gruppo }}'}, false, function(data){
              if(data.result){
                $("#follow_icon").attr("class","fa fa-bell");
                $("#follow strong").text("Smetti di seguire");
              }else{
                $("#follow_icon").attr("class","far fa-bell");
                $("#follow strong").text("Inizia a seguire");
              }
            });
          }
          </script>
          @endif
        </div>
        @endauth
      <article class="block-article">
        <div class="block-title">
          <h1 class="text-uppercase">{{ $query->titolo }}</h1>
        </div>
        <p>Scritto da <a href="{{ url($autore->slug) }}">{{ $autore->name }} {{ $autore->surname }}</a></p>
          <div class="date-info">
            <span class="date"><i class="far fa-calendar-alt"></i> {{ $date }}</span>
            <span class="time"><i class="far fa-clock"></i> {{ $time }}</span><br/>
          </div>
        <hr/>
        <div class="block-body">
          {!! $query2->text !!}
        </div>
        <div class="both"></div>
        @if(!empty($query->tags))
        <div class="block-meta">
          <ul class="meta-tags">
            <span class="fa fa-tags"></span>
            @foreach($tags as $tag)
              <li><a href="{{ url('search/tag/'.$tag) }}">#{{ $tag }}</a></li>
            @endforeach
          </ul>
          @if($query->rating_count > 0)
          <div class="float-left d-inline">
            <span>{{ $query->rating }} / 5</span>
          </div>
          @endif
        </div>
        @endif
      <hr style="border-style:dotted"/>
      <div class="block-footer">
        <div class="socials">
          @if($hasRate)
          <!-- da sistemare -->
          <div class="rating d-inline">
            <span class="circle full"></span>
          </div>
          @endif
          <a id="share_on_facebook" href="https://www.facebook.com/share.php?u={{Request::url()}}" target="_blank">
            <span class="fa-2x fab fa-facebook-square"></span>
          </a>
          <a id="share_on_linkedin" href="https://www.linkedin.com/sharing/share-offsite/?url={{Request::url()}}" target="_blank">
            <span class="fa-2x fab fa-linkedin"></span>
          </a>
        </div>
      </div>
    </article>
    <hr/>
  </div>
  </div>
@endsection
