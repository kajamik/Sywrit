@extends('front.layout.app')

@section('title', $query->titolo. ' - ')

@php
  $autore = \App\Models\User::find($query->id_autore);
  if($query->id_gruppo > 0) {
    $editore = \App\Models\Editori::find($query->id_gruppo);
  }
@endphp

@section('description', str_limit(strip_tags($query->testo), 40, "..."))

@section('seo')

    <meta property="og:title" content="{!! $query->titolo !!} - {{ config('app.name') }}" />
    <meta property="og:description" content="{!! str_limit(strip_tags($query->testo), 40, '...') !!}" />
    <meta property="og:type" content="s" />
    <meta property="og:url" content="{{ Request::url() }}" />
    <meta property="og:image" content="{{ asset($query->getBackground()) }}" />
    <meta property="article:published_time" content="{{ $query->created_at }}" />
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
      <article class="block-article">
        <div class="block-title">
          <h1 class="text-uppercase">{{ $query->titolo }}</h1>
        </div>
        <p>Articolo generato dal sistema</p>
        <div class="date-info">
          <span class="date"><i class="far fa-calendar-alt"></i> {{ $date }}</span>
          <span class="time"><i class="far fa-clock"></i> {{ $time }}</span><br/>
        </div>
        <hr/>
        <div class="block-body">
          {!! $query->testo !!}
        </div>
        <hr style="border-style:dotted"/>
        <div class="both"></div>
        @if(!empty($query->tags))
        <div class="block-meta">
          <ul class="meta-tags">
            <span class="fa fa-tags"></span>
            @foreach($tags as $tag)
              <li><a href="{{ url('search/tag/'.$tag) }}">#{{ $tag }}</a></li>
            @endforeach
          </ul>
        </div>
        @endif
      <div class="block-footer">
        @if($query->created_at != $query->updated_at)
        <span>Modificato {{ $query->updated_at->diffForHumans() }}</span>
        @endif
        <div class="row pt-5">
        <div class="socials">
          <div class="col-lg-12 col-sm-12 col-xs-12">
            <a id="share_on_facebook" href="https://www.facebook.com/share.php?u={{Request::url()}}" target="_blank">
              <span class="fa-2x fab fa-facebook-square"></span>
            </a>
            <a id="share_on_linkedin" href="https://www.linkedin.com/sharing/share-offsite/?url={{Request::url()}}" target="_blank">
              <span class="fa-2x fab fa-linkedin"></span>
            </a>
        </div>
      </div>
    </div>
  </article>

  </div>
  </div>
@endsection
