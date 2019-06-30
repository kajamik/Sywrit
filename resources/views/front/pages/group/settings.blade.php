@extends('front.layout.app')

@section('main')
<style>
#nav > li {
  display: inline-block;
  margin-top: 5px;
  margin-bottom: 10px;
  font-size: 20px;
}
#nav > li:not(:last-child)::after {
  content: '\00a0|';
}
</style>
  <div class="publisher-home">
    <section class="publisher-header" style="background-image: url({{ $query->getBackground() }})">
      <div class="container">
        <div class="publisher-logo">
          <div class="row">
            <div class="d-inline">
              <img src="{{ $query->getAvatar() }}" alt="Logo">
            </div>
            <div class="col-lg-10 col-sm-col-xs-12">
              <div class="mt-2 info">
                <span>{{ $query->name }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <nav class="publisher-nav">
      <ul id="nav">
        <li><a href="{{ url('publisher/'.$query->slug) }}">Home</a></li>
        <li><a class="@if($tab == 'edit') active @endif" href="{{ url('publisher/'.$query->slug.'/settings/edit') }}">Modifica gruppo</a></li>
        <li><a class="@if($tab == 'role') active @endif" href="{{ url('publisher/'.$query->slug.'/settings/role') }}">Gestione ruoli</a></li>
      </ul>
    </nav>
    <div class="publisher-body">
      <div class="container">
        <div class="container my-5">
          @include('front.pages.group.tabs.'.$tab)
        </div>
      </div>
    </div>
  </div>
@endsection
