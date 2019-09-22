@extends('front.layout.app')

@php
  $autore = \App\Models\User::find($query->id_autore);
  if($query->id_gruppo > 0)
    $editore = \App\Models\Editori::find($query->id_gruppo);
@endphp

@section('main')
    <div class="publisher-body">
      <a href="{{ url('groups/'. $query->group_id.'/article/'. $query->id) }}">{{ __('label.article.cancel_changes') }}</a>
      <form method="post" action="" enctype="multipart/form-data">
        @csrf

        <div class="mt-5">
          <div class="form-group row">
            <div class="col-md-12">
              <input id="title" type="text" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" name="title" value="{!! $query->title !!}" disabled>
            </div>
          </div>

          <div class="form-group row">
            <div class="col-md-12">
              <label for="file-upload" class="form-control custom-upload">
                <i class="fa fa-cloud-upload-alt"></i> {{ __('button.upload_file') }}
              </label>
              <input id="file-upload" type="file" onchange="App.upload(this.nextElementSibling, false)" name="image">
              <div id="image_preview" class="preview_body">
                {{ $query->cover }}
              </div>
            </div>
          </div>

          <div class="form-group row">
            <div class="col-md-12">
              <div class="document">
                {!! $query->text !!}
              </div>
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
