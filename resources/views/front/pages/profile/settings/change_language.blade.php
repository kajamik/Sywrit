@php
  SEOMeta::setTitle('Preferenza lingua - Sywrit', false);
@endphp

<form method="post" action="{{ url('settings/change_language') }}">
  @csrf

  <div class="text-center">
    <p>@lang('label.notice.language_preference')</p>
  </div>
  <div class="form-group row">
    <label for="name" class="col-md-4 col-form-label text-md-right">@lang('label.select_language')</label>
    <div class="col-md-6">
      <select class="form-control" name="lang">
      @foreach($lang as $value)
        <option value="{{ $value }}" @if(session()->get('locale') == $value) selected @endif>@lang('label.lang.'.$value)</option>
      @endforeach
      </select>
    </div>
  </div>
  <div class="form-group row">
    <div class="col-md-6 offset-md-4">
      <button type="submit" class="btn btn-info btn-block">
        Salva Impostazioni
      </button>
    </div>
  </div>
</form>
