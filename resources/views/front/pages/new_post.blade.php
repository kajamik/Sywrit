@extends('front.layout.app')

@section('title', 'Nuovo articolo -')

@section('main')
<style type="text/css">
#header {z-index:999}
#_pr_sel_, #_pr_sel_ * {
  font-family: 'Font Awesome\ 5 Free';
  font-weight: 900;
}
.document {
  padding: 15px;
  width: 100%;
  min-height: 170px;
  border-radius: 4px;
}
</style>
<div class="container">
  <div class="publisher-home">
    <div class="publisher-body">
  <form method="post" action="" enctype="multipart/form-data">
    @csrf

  <div class="mt-5">
    <div class="form-group row">
        <div class="col-md-12">
            <input id="title" type="text" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" name="document__title" placeholder="Titolo Articolo" required autofocus>
            @if ($errors->has('title'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('title') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group row">
      <label for="_au_sel_" class="col-md-4 col-form-label">Pubblica come</label>
      <div class="col-md-12">
        <select id="_au_sel" name="_au" class="form-control">
          <option value="1">{{\Auth::user()->nome}} {{\Auth::user()->cognome}}</option>
          @if(\Auth::user()->haveGroup())
          <option value="2">{{\Auth::user()->getPublisherInfo()->nome }}</option>
          @endif
        </select>
      </div>
    </div>

    {{--<div class="form-group row">
      <label for="_pr_sel_" class="col-md-4 col-form-label">Stato</label>
      <div class="col-md-12">
        <select id="_pr_sel_" name="_pr" class="form-control">
          <option value="1">&#xf0ac; Pubblico</option>
          <option value="2">&#xf023; Privato</option>
        </select>
      </div>
    </div>--}}

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
          <textarea class="document" name="document__text"></textarea>
        </div>
    </div>

    <div class="form-group row">
      <label for="tags" class="col-md-4 col-form-label"><span class="fa fa-tag"></span> Etichette</label>
        <div class="col-md-12">
          <input type="text" class="form-control" name="tags" placeholder="Moda Bellezza ..." />
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
</div>
<script>
$("#file-upload").change(function(){
    $("<div/>").html("<div class='preview_body'><div class='image-wrapper' id='preview-wrapper'><img id='image' src="+URL.createObjectURL(event.target.files[0])+"></div></div>").appendTo($("#image_preview"));
    $('#image').rcrop();
});
</script>
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
