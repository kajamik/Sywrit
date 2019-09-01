@extends('front.layout.app')

@php
  $autore = \App\Models\User::find($query->author_id);
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
      <a href="{{ url('groups/'. $query->group_id) }}" title="Back to group">
        <i class="fa fa-2x fa-long-arrow-alt-left"></i>
      </a>
        @auth
        <div class="publisher-info">
          @if($query->author_id == Auth::user()->id)
            @include('front.components.article.options')
          @endif
        </div>
        @endauth
      <div class="row">
        <div class="col-lg-9 col-md-9">
        <article class="block-article">
          <div class="block-title">
            <h1 class="text-uppercase">{{ $query->titolo }}</h1>
          </div>
          <p>@lang('label.article.written_by', ['name' => $autore->name.' '.$autore->surname, 'url' => url($autore->slug)])</p>
          <div class="date-info">
            <span class="date"><i class="far fa-calendar-alt"></i> {{ $date }}</span>
            <span class="time"><i class="far fa-clock"></i> {{ $time }}</span><br/>
          </div>
          <hr/>
          <div class="block-body">
            {!! $query->text !!}
          </div>
      </article>
    </div>
      <div class="col-lg-3 col-md-3">
        <div class="position-sticky sticky-top" style="top:63px">
          <div class="card">
            <div class="card-header bg-sw">
              {!! $autore->getRealName() !!}
            </div>
            <div class="card-body">

              <div class="text-center">
                <img src="{{ $autore->getAvatar() }}" alt="Avatar di {{ $autore->name }} {{ $autore->surname }}" />
                <hr/>
                @if(!empty($autore->biography))
                  <p>{!! $autore->biography !!}</p>
                @endif
              </div>
              @if($autore->getSocialLinks()->isNotEmpty())
                @foreach($autore->getSocialLinks() as $value)
                <li><a href="{{ url($value['url']) }}" target="_blank">
                  <i class="{{ $value['icon'] }}"></i> {{ $value['name'] }}
                </a></li>
                @endforeach
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>
@endsection
