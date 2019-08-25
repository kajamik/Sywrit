  <ul class="d-flex bg-sw p-2 mb-3">
    <li><a id="edt" href="{{ url('post/'.$query->slug.'/edit') }}">@lang('label.article.edit')</a></li>
    <li><a id="dlt" class="ml-2" href="#" onclick="link(this,'{{ route('article/action/delete') }}')">@lang('label.article.delete')</a></li>
    @if(get_class($query) == 'App\Models\SavedArticles')
    <li><a id="pub" class="ml-2" href="#" onclick="link(this, '{{ route('article/action/publish') }}')">@lang('label.article.publish')</a></li>
    @endif
  </ul>
<script>
function link(e, route){var el = setNode(e, {html: {"id": "__form__","action": route,"method": "post"}}, "form");setNode(el.html, {html: {"name": "id","value": "{{ $query->id }}"}}, "input");
$("#"+el.html.id).css('display','none');$("<div/>").html('{{ csrf_field() }}').appendTo($("#"+el.html.id)); $("#"+el.html.id).submit();}
</script>
