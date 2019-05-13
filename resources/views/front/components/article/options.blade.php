<a data-toggle="dropdown" href="#">
    <i class="fas fa-cog"></i> Opzioni Articolo
  </a>
  <div class="dropdown-menu" id="nodes">
    <a id="edt" class="dropdown-item" href="{{ url('post/'.$query->slug.'/edit') }}">Modifica articolo</a>
    <a id="dlt" class="dropdown-item" href="#" onclick="link(this,'{{ route('article/action/delete') }}')">Elimina articolo</a>
  </div>

<script>
function link(e, route){
  var el = setNode(e, {
    html: {
      "id": "__form__",
      "action": route,
      "method": "post"
    }
  }, "form");
  setNode(el.html, {
    html: {
      "name": "id",
      "value": "{{ $query->id }}"
    }
  }, "input");
$("<div/>").html('{{ csrf_field() }}').appendTo($("#"+el.html.id)); $("#"+el.html.id).submit();
}
</script>
