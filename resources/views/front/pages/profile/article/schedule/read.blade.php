@extends('front.layout.app')

@php
  $autore = \App\Models\User::find($query->id_autore);
  if($query->id_gruppo > 0) {
    $editore = \App\Models\Editori::find($query->id_gruppo);
  }
@endphp

@section('main')
<style>
.block-body {
  padding: 12px;
  min-height: 18em;
}
.feeds {
  padding: 15px;
}
</style>
  <div class="publisher-home">
    <div class="publisher-body">
        @auth
        <div class="publisher-info">
            @if($query->id_gruppo > 0 && Auth::user()->hasMemberOf($query->id_gruppo) || $query->id_autore == Auth::user()->id)
            <ul class="d-flex bg-sw p-2 mb-3">
              <li><a id="edt" href="{{ url('/articles/schedule/edit/'. $query->id) }}">@lang('label.article.edit')</a></li>
              <li><a id="dlt" class="ml-2" href="#" onclick="link(this,'{{ url('articles/schedule/delete/'. $query->id) }}')">@lang('label.article.delete')</a></li>
            </ul>
            <script>
            function link(e, route){var el = setNode(e, {html: {"id": "__form__","action": route,"method": "post"}}, "form");setNode(el.html, {html: {"name": "id","value": "{{ $query->id }}"}}, "input");
            $("#"+el.html.id).css('display','none');$("<div/>").html('{{ csrf_field() }}').appendTo($("#"+el.html.id)); $("#"+el.html.id).submit();}
            </script>
            @endif
        </div>
        @endauth
      <article class="block-article">
        <div class="block-title">
          <h1 class="text-uppercase">{{ $query->titolo }}</h1>
        </div>
        @if($query->id_gruppo > 0)
        <p>@lang('label.article.published_by', ['name' => $editore->name, 'url' => url($editore->slug)])</p>
        @endif
        <p>@lang('label.article.written_by', ['name' => $autore->name.' '.$autore->surname, 'url' => url($autore->slug)])</a></p>
        <div class="date-info">
          <span>L'articolo sarà pubblicato <span id="date">{{ \Carbon\Carbon::parse($query->scheduled_at)->translatedFormat('l j F Y')  }}</span> alle ore <span id="time">{{ \Carbon\Carbon::parse($query->scheduled_at)->format('H:i')  }}</span></span>
          <span class="btn btn-link" onclick="schedule()">[@lang('Modifica data')]</span>
        </div>
        <hr/>
        <div class="block-body">
          {!! $query->testo !!}
        </div>
        <hr style="border-style:dotted"/>
        <div class="both"></div>
        <div class="auth">
          @if($query->license == "1")
          <p>&copy; Sywrit Standard</p>
          @else
          <img src="{{ asset('upload/icons/cc.png') }}" title="@lang('Creative Commons BY SA') }}" alt="Creative Commons BY SA" />
          @endif
        </div>
        @if(!empty($query->tags))
        <div class="block-meta">
          <ul class="meta-tags">
            <span class="fa fa-tags"></span>
            @foreach($tags as $tag)
              <li><a href="{{ url('search/tag/'.$tag) }}">#{{ $tag }}</a></li>
            @endforeach
          </ul>
        </div>
        @endif
      <div class="block-footer">
        @if($query->created_at != $query->updated_at)
        <span>@lang('label.article.last_modified', ['time' => $query->updated_at->diffForHumans()])</span>
        @endif
      </div>
    </article>
  </div>
</div>
<script>
function schedule() {
  var dialog = App.getUserInterface({
    "ui": {
      "header":{"action":"{{url('ajax/article/action/schedule')}}","method":"GET"},
      "data":{"a_id":"{{$query->id}}","date":"#dateControl###val","time":"#timeControl###val"},
      "title": 'Modifica data',
      "content": [
        {"type": ["h5"], "text": "Data di pubblicazione"},
        {"type": ["input", "date"], "id": "dateControl", "class": "form-control", "value": "{{ \Carbon\Carbon::parse($query->scheduled_at)->toDateString() }}", "required": true},
        {"type": ["input", "time"], "id": "timeControl", "class": "form-control", "value": "{{ \Carbon\Carbon::parse($query->scheduled_at)->format('H:i') }}", "required": true},
        {"type": ["button", "submit"], "text": "Modifica", "class": "btn btn-primary btn-block"},
        {"type": ["p"], "text": "oppure", "class": "text-center"},
        {"type": ["button", "button"], "id": "rSchedule", "text": "Rimuovi programmazione", "class": "btn btn-dark btn-block", "onclick": function() {
          App.query("get", "{{url('ajax/article/action/delete/schedule')}}",{id:"{{$query->id}}"},false, function(h) { return eval(h); });
        }}
      ],
      "done": function(data) {
        dialog.remove();
        $("body").css('overflow','auto');
        $("#date").text(data.date);
        $("#time").text(data.time);
      }
    }
  });
}
</script>
@endsection
