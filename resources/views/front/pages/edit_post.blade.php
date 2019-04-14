@extends('front.layout.app')

@section('title', $query->titolo.' -')

@php
  $autore = \App\Models\User::find($query->id_autore);
  if($query->id_gruppo > 0)
    $editore = \App\Models\Editori::find($query->id_gruppo);
@endphp

@section('main')
<div class="container">
    <div class="publisher-body">
      <a href="{{url('read/'.$query->slug)}}">Annulla modifiche</a>
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
                <i class="fa fa-cloud-upload-alt"></i> Carica copertina
              </label>
              <input id="file-upload" type="file" name="image">
              <div id="image_preview" class="preview"></div>
            </div>
          </div>

          <div class="form-group row">
            <div class="col-md-12">
              <textarea class="document" name="document__text">{{ $query->testo }}</textarea>
            </div>
          </div>

          <div class="form-group row">
            <div class="col-md-6 offset-md-4">
                <button type="submit" class="btn btn-primary">
                    {{ __('Modifica') }}
                </button>
            </div>
          </div>

      </form>
      </div>
    </div>
  </div>
</div>

<link rel="stylesheet" href="{{ asset('plugins/dist/summernote.css') }}" />
<script src="{{ asset('plugins/dist/summernote.min.js') }}"></script>
<script>
$(".document").summernote({
  height: 250,
  toolbar: [
    ['style'],['style', ['bold', 'italic', 'underline']],['color', ['color']],['para', ['ul', 'ol', 'paragraph']]
    ,['link'],['picture'],['help']
  ],
  placeholder: 'Inizia a scrivere',
});
</script>
@endsection
