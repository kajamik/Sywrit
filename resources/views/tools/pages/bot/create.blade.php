@extends('tools.layout.app')

@section('title', 'Nuovo articolo')

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
      <label for="title" class="col-md-4 col-form-label required">Titolo Articolo</label>
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
          @if ($errors->has('document__title'))
              <span class="invalid-feedback" role="alert">
                  <strong>{{ $errors->first('document__text') }}</strong>
              </span>
          @endif
          <div id="editor" class="document{{ $errors->has('document__text') ? ' is-invalid' : '' }}" name="document__text">
          </div>
        </div>
    </div>

    <div class="form-group">
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
  <link rel="stylesheet" href="{{ asset('plugins/editor/css/editor.css') }}" />
  <script src="{{ asset('plugins/editor/js/editor.js') }}"></script>
  <script>
    $Editor.Init({
        editor: '.document',
        height: 460,
        toolbar: ['header', 'bold', 'italic']
    });

    /*$Editor.Plugins({
      // plugin1, plugin2, ...
      'mention': {
        'marked': '@',
        'sends': [
          {id: '@paolo', name: 'Paolo'},
          {id: '@pagu', name: 'Filippo'}
        ]
      }
    });*/
  </script>
{{--<link rel="stylesheet" href="{{ asset('plugins/dist/summernote.css') }}" />
<script src="{{ asset('plugins/dist/summernote.min.js') }}"></script>
<script>var initial_form_state, last_form_state;$(".document").summernote({height: 500,
toolbar:[['style'],['style', ['bold', 'italic', 'underline']],['color', ['color']],['para', ['ul', 'ol', 'paragraph']],['link'],['picture'],['help']],placeholder: 'Inizia a scrivere',
callbacks:{onChange:function(){last_form_state = $('form').serialize();}}});</script>
<script src="{{ asset('plugins/editor/ckeditor.js') }}"></script>
<script>
var initial_form_state, last_form_state;
	ClassicEditor.create(document.querySelector('.document'), {
    plugins: ['Base64UploadAdapter']
  }
);
  $(window).bind('beforeunload', function(e) {if(last_form_state != initial_form_state){return false;}});$(document).on("submit","form",function(event){$(window).off('beforeunload');});
</script>
--}}
@endsection
