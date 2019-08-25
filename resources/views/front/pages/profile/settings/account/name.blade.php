@php
  SEOMeta::setTitle('Informazioni personali - Sywrit', false);
@endphp

@if(Session::get("successful_changes"))
<div class="alert alert-success">
  <p class="fa fa-check"> @lang("label.notice.successful_changes")</p>
</div>
@endif

<form method="post" action="{{ url('settings/account/name') }}" enctype="multipart/form-data">
  @csrf
  <div class="form-group row">
    <label for="name" class="col-md-4 col-form-label text-md-right">@lang('label.account.name')</label>
    <div class="col-md-6">
      <input type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ Auth::user()->name }}">
      @if($errors->has('name'))
          <span class="invalid-feedback" role="alert">
              <strong>{{ $errors->first('name') }}</strong>
          </span>
      @endif
    </div>
  </div>
  <div class="form-group row">
    <label for="surname" class="col-md-4 col-form-label text-md-right">@lang('label.account.surname')</label>
    <div class="col-md-6">
      <input type="text" class="form-control{{ $errors->has('surname') ? ' is-invalid' : '' }}" name="surname" value="{{ Auth::user()->surname }}">
      @if($errors->has('surname'))
          <span class="invalid-feedback" role="alert">
              <strong>{{ $errors->first('surname') }}</strong>
          </span>
      @endif
    </div>
  </div>
  <div class="form-group row">
    <label for="cover" class="col-md-4 col-form-label text-md-right">@lang('label.account.cover_image')</label>
    <div class="col-md-6" id="cover">
      <label for="file-upload" class="form-control custom-upload{{ $errors->has('cover') ? ' is-invalid' : '' }}">
        <span class="fa fa-cloud-upload-alt"></span> Carica file
      </label>
      <input id="file-upload" type="file" onchange="App.upload(this.nextElementSibling, false)" name="cover">
      @if($errors->has('cover'))
          <span class="invalid-feedback" role="alert">
              <strong>{{ $errors->first('cover') }}</strong>
          </span>
      @endif
      <div id="preview_cover" class="preview_body"></div>
    </div>
  </div>

  <div class="form-group row">
    <label for="avatar" class="col-md-4 col-form-label text-md-right">@lang('label.account.profile_image')</label>
    <div class="col-md-6" id="avatar">
      <label for="file-upload2" class="form-control custom-upload{{ $errors->has('avatar') ? ' is-invalid' : '' }}">
        <span class="fa fa-cloud-upload-alt"></span> @lang('button.upload_file')
      </label>
      <input id="file-upload2" type="file" onchange="App.upload(this.nextElementSibling, true)" name="avatar">
      @if($errors->has('avatar'))
          <span class="invalid-feedback" role="alert">
              <strong>{{ $errors->first('avatar') }}</strong>
          </span>
      @endif
      <div id="preview_avatar" class="preview_body"></div>
    </div>
  </div>

  {{--@if(Auth::user()->avatar)
  <div class="form-group row">
    <div class="col-md-6 text-md-right">
      <a href="#photo">Elimina foto di profilo</a>
    </div>
  </div>
  @endif--}}

  <div class="form-group row">
    <label for="bio" class="col-md-4 col-form-label text-md-right">@lang('label.account.bio')</label>

    <div class="col-md-6">
      <textarea id="bio" class="form-control{{ $errors->has('bio') ? ' is-invalid' : '' }}" name="bio" placeholder="Inserisci la tua biografia">{!! Auth::user()->biography !!}</textarea>
      @if($errors->has('bio'))
          <span class="invalid-feedback" role="alert">
              <strong>{{ $errors->first('bio') }}</strong>
          </span>
      @endif
    </div>
  </div>
  <hr/>

  <div class="form-group row">
    <label class="col-md-4 col-form-label text-md-right">@lang('label.account.social_title')</label>

    <div id="s_group" class="col-md-6 col-sm-12">
      @if($my_apps->count())
      @foreach($my_apps as $value)
      <div class="pb-2 row">
        <div class="col-md-5 col-12">
          <input class="form-control" name="social_account_name[{{ $value->id }}]" placeholder="@lang('label.account.social_placeholder')" value="{{ $value->url }}"/>
        </div>
        <div class="col-md-5 col-12">
          <select class="form-control" name="social_service_name[{{ $value->id }}]">
            @foreach($apps as $value2)
            <option value="{{ $value2->id }}" @if($value->app->name == $value2->name) selected @endif>{{ ucfirst($value2->name) }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-1 col-12">
          <button type="button" class="btn remove_app" role="button">
            <i class="fa fa-minus-circle"></i>
          </button>
        </div>
      </div>
      @endforeach
      @else
      @include('front.components.social_links')
      @endif
    </div>

    <div class="offset-md-5 col">
      <button type="button" id="add_social" class="btn btn-link" role="button"><i class="fa fa-plus"></i> @lang('button.add_social_link')</button>
    </div>
  </div>

  <div class="form-group row">
    <div class="col-md-6 offset-md-4">
      <button type="submit" class="btn btn-info btn-block">
        @lang("button.save_settings")
      </button>
    </div>
  </div>
</form>

@section('js')
<script>
var d = $("#s_group").children().first().clone();
$("#add_social").on('click', function() {
  $.get('{{ url("ajax/account/add_social_address") }}', function(data) {
    $("#s_group").append(data);
  });
});
$("#s_group").on('click', 'button', function(e) {
  $(this).parent().parent().closest("div").remove();
  if($("#s_group").children().length < 1) {
    $.get('{{ url("ajax/account/add_social_address") }}', function(data) {
      $("#s_group").append(data);
    });
  }
});
</script>
@endsection
