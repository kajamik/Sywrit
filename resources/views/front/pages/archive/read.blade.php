@extends('front.layout.app')

@php
  $autore = \App\Models\User::find($query->id_autore);
  if($query->id_gruppo > 0) {
    $editore = \App\Models\Editori::find($query->id_gruppo);
  }
@endphp

@section('main')
<style>
.block-body {
  padding: 12px;
  min-height: 18em;
}
.feeds {
  padding: 15px;
}
</style>
  <div class="publisher-home">
    <div class="publisher-body">
        @auth
        <div class="publisher-info">
            @if($query->id_gruppo > 0 && Auth::user()->hasMemberOf($query->id_gruppo) || $query->id_autore == Auth::user()->id)
              @include('front.components.article.options')
            @endif
        </div>
        @endauth
      <article class="block-article">
        <div class="block-title">
          <h1 class="text-uppercase">{{ $query->titolo }}</h1>
        </div>
        @if($query->id_gruppo > 0)
        <p>@lang('label.article.published_by', ['name' => $editore->name, 'url' => url($editore->slug)])</p>
        @endif
        <p>@lang('label.article.written_by', ['name' => $autore->name.' '.$autore->surname, 'url' => url($autore->slug)])</a></p>
        <div class="date-info">
          <span>@lang('label.article.no_published')</span>
        </div>
        <hr/>
        <div class="block-body">
          {!! $query->testo !!}
        </div>
        <hr style="border-style:dotted"/>
        <div class="both"></div>
        <div class="auth">
          @if($query->license == "1")
          <p>&copy; Sywrit Standard</p>
          @else
          <img src="{{ asset('upload/icons/cc.png') }}" title="@lang('Creative Commons BY SA') }}" alt="Creative Commons BY SA" />
          @endif
        </div>
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
        <span>@lang('label.article.last_modified', ['time' => $query->updated_at->diffForHumans()])</span>
        @endif
      </div>
    </article>
  </div>
</div>
@endsection
