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
  #nav > li.active a {
    color: #fff;
  }
  </style>
  <div class="publisher-home">
    <div class="publisher-header" style="background-image: url({{ Auth::user()->getBackground() }})">
      <div class="container">
        <div class="publisher-logo">
          <div class="row">
            <div class="d-inline">
              <img src="{{ Auth::user()->getAvatar() }}" alt="Logo">
            </div>
            <div class="ml-2 mt-2 info">
              <span>
                {!! Auth::user()->getRealName() !!}
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
    <nav class="publisher-nav">
      <ul id="nav">
        <li @if(!isset($slug)) class="active" @endif><a href="{{ url('settings') }}">@lang('label.menu.settings')</a></li>
        <li @if(isset($slug) && $slug == "account") class="active" @endif><a href="{{ url('settings/account') }}">@lang('label.setting.account')</a></li>
        @if(empty(Auth::user()->social_auth_id))
        <li @if(isset($slug) && $slug == "change_password") class="active" @endif><a href="{{ url('settings/change_password') }}">@lang('label.setting.password')</a></li>
        @endif
        <li @if(isset($slug) && $slug == "change_language") class="active" @endif><a href="{{ url('settings/change_language') }}">@lang('label.setting.language')</a></li>
      </ul>
    </nav>
    <div class="publisher-body pt-3">
      <div class="container">

      @if(isset($slug))
        @include('front.pages.profile.settings.'.$slug)
      @else
        <div class="col-lg-12">
          <div class="card">
            <a href="{{ url('settings/account') }}">
              <div class="card-body">
                <h5 class="card-title">@lang('label.setting.account')</h4>
                <p>@lang('label.setting.account_description')</p>
              </div>
            </a>
          </div>
        </div>
        @if(empty(Auth::user()->social_auth_id))
        <div class="col-lg-12">
          <div class="card">
            <a href="{{ url('settings/change_password') }}">
              <div class="card-body">
                <h5 class="card-title">@lang('label.setting.password')</h4>
                <p>@lang('label.setting.password_description')</p>
              </div>
            </a>
          </div>
        </div>
        @endif
        <div class="col-lg-12">
          <div class="card">
            <a href="{{ url('settings/change_language') }}">
              <div class="card-body">
                <h5 class="card-title">@lang('label.setting.language')</h4>
                <p>@lang('label.setting.language_description')</p>
              </div>
            </a>
          </div>
        </div>
        @endif

      </div>
    </div>
  </div>
@endsection
