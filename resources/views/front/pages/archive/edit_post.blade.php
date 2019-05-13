@extends('front.layout.app')

@php
  $autore = \App\Models\User::find($query->id_autore);
  if($query->id_gruppo > 0)
    $editore = \App\Models\Editori::find($query->id_gruppo);
@endphp

@section('main')
    <div class="publisher-body">
      <a href="{{url('read/archive/'.$query->slug)}}">Annulla modifiche</a>
      <form method="post" action="" enctype="multipart/form-data">
        @csrf

        <div class="mt-5">
          <div class="form-group row">
            <div class="col-md-12">
              <input id="title" type="text" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" name="document__title" value="{!! $query->titolo !!}">
            </div>
          </div>

          <div class="form-group row">
            <div class="col-md-12">
              <label for="file-upload" class="form-control custom-upload">
                <i class="fa fa-cloud-upload-alt"></i> Carica copertina
              </label>
              <input id="file-upload" type="file" onchange="App.upload(this.nextElementSibling, false)" name="image">
              <div id="image_preview" class="preview_body"></div>
            </div>
          </div>

          <div class="form-group row">
            <div class="col-md-12">
              <textarea class="document" name="document__text">{{ $query->testo }}</textarea>
            </div>
          </div>

          <div class="form-group row">
            <label for="_ct_sel_" class="col-md-4 col-form-label">Selezione categoria</label>
              <div class="col-md-12">
                <select id="_ct_sel_" class="form-control" name="_ct_sel_">
                  <option selected>Seleziona una categoria</option>
                  @foreach($categories as $value)
                  <option value="{{ $value->id }}" @if($value->id == $query->topic_id) selected @endif>{{ $value->name }}</option>
                  @endforeach
                </select>
              </div>
          </div>

          <div class="form-group row">
            <label for="_l_sel_" class="col-md-4 col-form-label">Tipo di licenza <span class="fa fa-info-circle" data-script="info" data-text="Esistono due tipi di licenza:<br/><br/>Sywrit Standard: Consente di impostare una licenza proprietaria sul tuo articolo;<br/><br/>Creative Commons BY SA: Permette agli altri di distribuire, modificare e sviluppare anche commercialmente l'opera, licenziandola con gli stessi termini dell'opera originale, riconoscendo sempre l'autore;"></span></label>
              <div class="col-md-12">
                <select id="_l_sel_" class="form-control" name="_l_sel_">
                  <option value="1" @if($query->license == '1') selected @endif>Sywrit Standard</option>
                  <option value="2" @if($query->license == '2') selected @endif>Creative Commons</option>
                </select>
              </div>
          </div>

          <div class="form-group row">
            <label for="tags" class="col-md-4 col-form-label"><span class="fa fa-tag"></span> Etichette</label>
              <div class="col-md-12">
                <input type="text" class="form-control" name="tags" value="{!! str_replace(',', ' ', $query->tags) !!}" placeholder="&quot;globalwarming climatestrike&quot; risulterà come #globalwarming #climatestrike" />
              </div>
          </div>

          <div class="form-group row">
            <div class="col-md-6 offset-md-4">
                <button type="submit" class="btn btn-primary">
                    {{ __('Salva modifiche') }}
                </button>
            </div>
          </div>

      </form>
      </div>
    </div>
  </div>

<link rel="stylesheet" href="{{ asset('plugins/dist/summernote.css') }}" />
<script src="{{ asset('plugins/dist/summernote.min.js') }}"></script>
<script>var initial_form_state, last_form_state;$(".document").summernote({height: 165,
toolbar:[['style'],['style', ['bold', 'italic', 'underline']],['color', ['color']],['para', ['ul', 'ol', 'paragraph']],['link'],['picture'],['help']],placeholder: 'Inizia a scrivere',
callbacks:{onChange:function(){last_form_state = $('form').serialize();}}});$(window).bind('beforeunload', function(e) {if(last_form_state != initial_form_state){return false;}});$(document).on("submit","form",function(event){$(window).off('beforeunload');});</script>
@endsection
