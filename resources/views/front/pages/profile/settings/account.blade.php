@if(isset($slug2))
  @include('front.pages.profile.settings.account.'.$slug2)
@else
  <div class="col-lg-12">
    <div class="card">
      <a href="{{ url('settings/account/name') }}">
        <div class="card-body">
          <h5 class="card-title">@lang('label.setting.account_info')</h4>
          <p>@lang('label.setting.account_info_description')</p>
        </div>
      </a>
    </div>
  </div>

  {{-- <div class="col-lg-12">
    <div class="card">
      <a href="{{ url('settings/account/username') }}">
        <div class="card-body">
          <h5 class="card-title">@lang('label.setting.account_user')</h4>
          <p>@lang('label.setting.account_user_description', ['web' => config('app.url'), 'name' => Auth::user()->slug])</p>
        </div>
      </a>
    </div>
  </div>--}}

  <div class="col-lg-12">
    <div class="card">
      <a href="{{ url('account_delete') }}">
        <div class="card-body">
          <h5 class="card-title">@lang('label.setting.account_deletion')</h4>
          <p>@lang('label.setting.account_deletion_description')</p>
        </div>
      </a>
    </div>
  </div>
@endif
