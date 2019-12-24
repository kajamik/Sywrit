@extends('front.layout.app')

@section('main')
<div class="publisher-body">
  <a href="{{ url('/articles/schedule/view/'.$query->id) }}">@lang('label.article.cancel_changes')</a>
  <form method="post" action="" enctype="multipart/form-data">
    @csrf

    <div class="mt-5">
      <div class="form-group row">
        <div class="col-md-12">
          <input id="title" type="text" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" name="document__title" value="{!! $query->titolo !!}">
        </div>
      </div>

      <div class="form-group row">
        <div class="col-md-12">
          <label for="file-upload" class="form-control custom-upload">
            <i class="fa fa-cloud-upload-alt"></i> @lang('label.article.upload_file')
          </label>
          <input id="file-upload" type="file" onchange="App.upload(this.nextElementSibling, false)" name="image">
          <div id="image_preview" class="preview_body"></div>
        </div>
      </div>

      <div class="form-group row">
        <div class="col-md-12">
          <div class="document">
            {!! $query->testo !!}
          </div>
        </div>
      </div>

      <div class="form-group row">
        <label for="_ct_sel_" class="col-md-4 col-form-label">@lang('label.select_category')</label>
          <div class="col-md-12">
            <select id="_ct_sel_" class="form-control" name="_ct_sel_">
              <option selected>@lang('form.select_a_category')</option>
              @foreach($categories as $value)
              <option value="{{ $value->id }}" @if($value->id == $query->topic_id) selected @endif>{{ $value->name }}</option>
              @endforeach
            </select>
          </div>
      </div>

      <div class="form-group row">
        <label for="_l_sel_" class="col-md-4 col-form-label">@lang('label.article.license_type') <span class="fa fa-info-circle" data-script="info" data-text="@lang('label.notice.license_info')"></span></label>
          <div class="col-md-12">
            <select id="_l_sel_" class="form-control" name="_l_sel_">
              <option value="1" @if($query->license == '1') selected @endif>Sywrit Standard</option>
              <option value="2" @if($query->license == '2') selected @endif>Creative Commons</option>
            </select>
          </div>
      </div>

      <div class="form-group row">
        <label for="tags" class="col-md-4 col-form-label"><span class="fa fa-tag"></span> Tags</label>
          <div class="col-md-12">
            <input type="text" class="form-control" name="tags" value="{!! str_replace(',', ' ', $query->tags) !!}" placeholder="&quot;globalwarming climatestrike&quot; risulterà come #globalwarming #climatestrike" />
          </div>
      </div>

      <div class="form-group row">
        <div class="col-md-6 offset-md-4">
            <button type="submit" class="btn btn-primary">
                @lang('button.save_changes')
            </button>
        </div>
      </div>

  </form>
  </div>
</div>

@include('front.components.article.editor', ['editor' => '.document'])

@endsection
