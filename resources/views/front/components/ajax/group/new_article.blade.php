@extends('front.layout.app')

@section('main')
  <div class="publisher-home">
    <div class="publisher-body">
  <form method="post" action="" enctype="multipart/form-data">
    @csrf

    <div class="form-group row">
      <label for="title" class="col-md-4 col-form-label required">{{ __('label.article_title') }}</label>
        <div class="col-md-12">
            <input id="title" type="text" class="form-control{{ $errors->has('document__title') ? ' is-invalid' : '' }}" name="document__title" value="{{ old('document__title') }}" required autofocus>
            @if ($errors->has('document__title'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('document__title') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group row">
      <div class="col-md-12">
        <label for="file-upload" class="form-control{{ $errors->has('image') ? ' is-invalid' : '' }} custom-upload">
          <i class="fa fa-cloud-upload-alt"></i> {{ __('label.upload_cover') }}
        </label>
        <input id="file-upload" type="file" onchange="App.upload(this.nextElementSibling, false)" name="image">
        <div id="image_preview" class="preview_body"></div>
        @if ($errors->has('image'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first('image') }}</strong>
            </span>
        @endif
      </div>
    </div>

    <div class="form-group row">
        <div class="col-md-12">
          <div class="document{{ $errors->has('document__text') ? ' is-invalid' : '' }}">
            {{ old('document__text') }}
          </div>
          @if ($errors->has('document__text'))
              <span class="invalid-feedback" role="alert">
                  <strong>{{ $errors->first('document__text') }}</strong>
              </span>
          @endif
        </div>
    </div>

    <div class="form-group row">
        <div class="col-md-6 offset-md-4">
            <button type="submit" class="btn btn-primary">
                @lang('button.publish')
            </button>
            <button type="submit" class="btn btn-primary" name="save" value="1">
                @lang('button.save')
            </button>
        </div>
    </div>

  </form>
</div>
</div>

@include('front.components.article.editor', ['editor' => '.document'])

@endsection
