@section('css')
<link rel="stylesheet" href="https://uicdn.toast.com/tui-editor/latest/tui-editor.css" />
    <link rel="stylesheet" href="https://uicdn.toast.com/tui-editor/latest/tui-editor-contents.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.33.0/codemirror.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/styles/github.min.css" />
@endsection

@section('js')
<script src="https://uicdn.toast.com/tui-editor/latest/tui-editor-Editor-full.js"></script>
<script>
var initial_form_state, last_form_state;
var editor = new tui.Editor({
  el: document.querySelector('{{ $editor }}'),
  language: 'it_IT',
  initialEditType: 'markdown',
  previewStyle: 'tab',
  height: '500px',
  events: {
      change: function() {
        last_form_state = $('form').serialize();
      }
  }
});
$(window).bind('beforeunload', function(e) {if(last_form_state != initial_form_state){return false;}});
$(document).on("submit","form",function(event){$('[name=document__text]').attr('value', editor.getMarkdown());$(window).off('beforeunload');});
</script>
@endsection
