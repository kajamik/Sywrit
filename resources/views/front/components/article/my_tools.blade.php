<a data-toggle="dropdown" href="#">
    <i class="fas fa-cog"></i> Opzioni Articolo
  </a>
  <div class="dropdown-menu" id="nodes">
    @if(!$query->status)
    <a id="pb" class="dropdown-item" href="#" onclick="link(this,'{{ route('article/action/publish') }}')">Pubblica articolo</a>
    @endif
    <a id="edt" class="dropdown-item" href="{{ url('post/'.$query->id.'/edit') }}">Modifica articolo</a>
    <a id="dlt" class="dropdown-item" href="#" onclick="link(this,'{{ route('article/action/delete') }}')">Elimina articolo</a>
  </div>

<script>
function link(e, route){
  var el = setNode(e, {
    html: {
      "id": "__form__",
      "action": route,
      "method": "POST"
    }
  }, "form");

  setNode(el.html, {
    html: {
      "id": "_rq",
      "name": "_rq_token",
      "value": "{{$query->id}}"
    }
  }, "input");

  $("<div/>").html('{{ csrf_field() }}').appendTo($("#"+el.html.id));

  $("#"+el.html.id).submit();
}
</script>
