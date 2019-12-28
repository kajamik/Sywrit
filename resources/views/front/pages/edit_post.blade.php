@extends('front.layout.app')

@php
  $autore = \App\Models\User::find($query->id_autore);
  if($query->id_gruppo > 0)
    $editore = \App\Models\Editori::find($query->id_gruppo);
@endphp

@section('main')
    <div class="publisher-body">
      <a href="{{url('read/'.$query->slug)}}">{{ __('label.article.cancel_changes') }}</a>
      <form method="post" action="" enctype="multipart/form-data">
        @csrf

        <div class="mt-5">
          <div class="form-group row">
            <div class="col-md-12">
              <input id="title" type="text" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" name="title" value="{!! $query->titolo !!}" disabled>
            </div>
          </div>

          <div class="form-group row">
            <div class="col-md-12">
              <label for="file-upload" class="form-control custom-upload">
                <i class="fa fa-cloud-upload-alt"></i> {{ __('label.article.upload_file') }}
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
            <label for="tags" class="col-md-4 col-form-label"><span class="fa fa-tag"></span> Tags</label>
              <div class="col-md-12">
                <input type="text" class="form-control" name="tags" value="{!! str_replace(',', ' ', $query->tags) !!}" placeholder="&quot;globalwarming climatestrike&quot; risulterà come #globalwarming #climatestrike" value="{{ old('tags') }}" />
              </div>
          </div>

          <div class="form-group row">
            <div class="col-md-6 offset-md-4">
                <button type="submit" class="btn btn-primary">
                    {{ __('button.save_changes') }}
                </button>
            </div>
          </div>

      </form>
      </div>
    </div>

@include('front.components.article.editor', ['editor' => '.document'])

@endsection
