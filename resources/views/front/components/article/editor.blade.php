@section('css')
<link rel="stylesheet" href="{{ asset('editor/css/editor.css') }}" />
    <link rel="stylesheet" href="{{ asset('editor/css/editor-contents.css') }}" />
    <link rel="stylesheet" href="{{ asset('editor/css/codemirror.css') }}" />
@endsection

@section('js')
<script src="{{ asset('editor/js/editor.js') }}"></script>
<script>
var initial_form_state, last_form_state;
var $editor = document.querySelector('{{ $editor }}');
var editor = new tui.Editor({
  el: $editor,
  language: 'it_IT',
  initialEditType: 'wysiwyg',
  previewStyle: 'vertical',
  height: '500px',
  events: {
      change: function() {
        last_form_state = $('form').serialize();
      }
  }
});
$(window).bind('beforeunload', function(e) {if(last_form_state != initial_form_state){return false;}});
$(document).on("submit","form",function(event){$(this).append('<input type="hidden" name="document__text" />');$('[name=document__text]').attr('value', editor.getHtml());$(window).off('beforeunload');});
</script>
@endsection
