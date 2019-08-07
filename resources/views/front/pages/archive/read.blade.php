@extends('front.layout.app')

@php
  $autore = \App\Models\User::find($query->id_autore);
  if($query->id_gruppo > 0) {
    $editore = \App\Models\Editori::find($query->id_gruppo);
  }
@endphp

@section('main')
<style>
.publisher-body .block-article a, .publisher-body .block-article a:hover {
  text-decoration: underline;
}
div.date-info {
  margin-top: 12px;
}
span.date {
  text-transform: capitalize;
}
span.time {
  padding: 0;
}
.block-body {
  padding: 12px;
}
.feeds {
  padding: 15px;
}
.btn-custom {
  background-color: #fff;
  border: 1px solid #000;
  border-radius: 3px;
}
.btn-custom:active {
  outline: none;
}
._button_active_ {
  background-color: #A22932;
  color: #fff;
}
</style>
  <div class="publisher-home">
    <div class="publisher-body">
        @auth
        <div class="publisher-info">
            @if($query->id_gruppo > 0 && Auth::user()->hasMemberOf($query->id_gruppo) || $query->id_autore == Auth::user()->id)
            <a data-toggle="dropdown" href="#">
                <i class="fas fa-cog"></i> Opzioni Articolo
              </a>
              <div class="dropdown-menu" id="nodes">
                <a id="pb" class="dropdown-item" href="#" onclick="link(this,'{{ route('article/action/publish') }}')">Pubblica articolo</a>
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
          @endif
        </div>
        @endauth
      <article class="block-article">
        <div class="block-title">
          <h1 class="text-uppercase">{{ $query->titolo }}</h1>
        </div>
        @if($query->id_gruppo > 0)
        <p>Pubblicato da <a href="{{ url($editore->slug) }}">{{ $editore->name }}</a></p>
        @endif
        <p>Scritto da <a href="{{ url($autore->slug) }}">{{ $autore->name }} {{ $autore->surname }}</a></p>
        <div class="date-info">
          <span>Articolo non ancora pubblicato</span>
        </div>
        <hr/>
        <div class="block-body">
          @markdown {!! $query->testo !!} @endmarkdown
        </div>
        <hr style="border-style:dotted"/>
        <div class="both"></div>
        <div class="auth">
          @if($query->license == "1")
          <p>&copy; Produzione riservata</p>
          @else
          <img src="{{ asset('upload/icons/cc.png') }}" title="{{ trans('Licenza Creative Commons BY SA') }}" alt="License Creative Commons BY SA" />
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
        <span>Modificato {{ $query->updated_at->diffForHumans() }}</span>
        @endif
      </div>
    </article>
  </div>
</div>
@endsection
