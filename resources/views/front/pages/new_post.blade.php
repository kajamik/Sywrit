@extends('front.layout.app')

@section('main')
<style type="text/css">
.document {
  padding: 15px;
  width: 100%;
  min-height: 170px;
  border-radius: 4px;
}
</style>
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

    {{--<div class="form-group row">
      <label for="_au_sel_" class="col-md-4 col-form-label">Pubblica come</label>
      <div class="col-md-12">
        <select id="_au_sel" name="_au" class="form-control">
          <option value="0" class="ahr">{{ Auth::user()->name }} {{ Auth::user()->surname }}</option>
          @if(Auth::user()->haveGroup())
          @foreach(Auth::user()->getPublishersInfo() as $value)
            @if(!$value->suspended)
            <option value="{{ $value->id }}">{{ $value->name }}</option>
            @endif
          @endforeach
          @endif
        </select>
      </div>
    </div>--}}

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
      <label for="_ct_sel_" class="col-md-4 col-form-label">{{ __('label.select_category') }}</label>
        <div class="col-md-12">
          <select id="_ct_sel_" class="form-control" name="_ct_sel_">
            @if(!Request::get('_topic'))
            <option selected>{{ __('form.select_a_category') }}</option>
            @foreach($categories as $value)
            <option value="{{ $value->id }}">{{ __('label.'. $value->slug) }}</option>
            @endforeach
            @else
            <option value="{{ $categories->id }}">{{ $categories->name }}</option>
            @endif
          </select>
        </div>
    </div>

    <div class="form-group row">
      <label for="_l_sel_" class="col-md-4 col-form-label">{{ __('label.license_type') }} <span class="fa fa-info-circle" data-script="info" data-text="{!! __('label.license_info') !!}"></span></label>
        <div class="col-md-12">
          <select id="_l_sel_" class="form-control" name="_l_sel_">
            <option value="1">Sywrit Standard</option>
            <option value="2">Creative Commons</option>
          </select>
        </div>
    </div>

    <div class="form-group row">
      <label for="tags" class="col-md-4 col-form-label"><span class="fa fa-tag"></span> Etichette</label>
        <div class="col-md-12">
          <input type="text" class="form-control" name="tags" placeholder="&quot;globalwarming climatestrike&quot; risulterà come #globalwarming #climatestrike" value="{{ old('tags') }}"/>
        </div>
    </div>

    <div class="form-group row">
        <div class="col-md-6 offset-md-4">
            <button type="submit" class="btn btn-primary">
                {{ __('Pubblica') }}
            </button>
            <button type="submit" class="btn btn-primary" name="save" value="1">
                {{ __('Salva') }}
            </button>
        </div>
    </div>

  </form>
</div>
</div>

@include('front.components.article.editor', ['editor' => '.document'])

@endsection
