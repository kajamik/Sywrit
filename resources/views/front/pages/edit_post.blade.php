@extends('front.layout.app')

@section('title', $query->titolo.' -')

@php
  $autore = \App\Models\User::find($query->autore);
  if($query->id_gruppo > 0)
    $editore = \App\Models\Editori::find($query->id_gruppo);
@endphp

@section('main')
<style type="text/css">
#header {z-index:999}
</style>
<div class="container">
  <div class="publisher-home">
    @if(!empty($editore))
    <section class="publisher-header" style="background-image: url({{asset($editore->getBackground())}})">
      <div class="container">
        <img class="publisher-logo" src="{{asset($editore->getLogo())}}" alt="Logo">
        <div class="info">
          <span>{{$editore->nome}}</span>
        </div>
      </div>
    </section>
    @else
    <section class="publisher-header" style="background-image: url({{asset($autore->getBackground())}})">
      <div class="container">
        <img class="publisher-logo" src="{{asset($autore->getAvatar())}}" alt="Logo">
        <div class="info">
          <span>{{$autore->nome}} {{$autore->cognome}}</span>
        </div>
      </div>
    </section>
    @endif
    <section class="publisher-body">
      <a href="{{url('read/'.$query->slug)}}">Annulla modifiche</a>
      <form method="post" action="" enctype="multipart/form-data">
        @csrf

      <div class="mt-5">
        <div class="form-group row">
            <div class="col-md-12">
                <input id="title" type="text" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" name="title" value="{!! $query->titolo !!}" disabled>
            </div>
        </div>

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
              <div class="document">
                  <div class="document__toolbar"></div>
                  <div class="document__editable-container">
                      <textarea class="document__editable" name="document__text">{!! $query->testo !!}</textarea>
                  </div>
              </div>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-md-6 offset-md-4">
                <button type="submit" class="btn btn-primary">
                    {{ __('Modifica') }}
                </button>
            </div>
        </div>

      </form>
    </section>
  </div>
</div>
<script type="text/javascript">
(function(d){d['it']=Object.assign(d['it']||{},{a:"Impossibile caricare il file:",b:"Corsivo",c:"Grassetto",d:"Blocco citazione",e:"Immagine a dimensione intera",f:"Immagine laterale",g:"Immagine allineata a sinistra",h:"Immagine centrata",i:"Immagine allineata a destra",j:"Widget immagine",k:"Inserisci immagine o file",l:"Inserisci immagine",m:"Seleziona intestazione",n:"Intestazione",o:"Elenco numerato",p:"Elenco puntato",q:"inserire didascalia dell'immagine",r:"Caricamento fallito",s:"Collegamento",t:"Inserisci tabella",u:"Intestazione colonna",v:"Insert column left",w:"Insert column right",x:"Elimina colonna",y:"Colonna",z:"Riga d'intestazione",aa:"Inserisci riga sotto",ab:"Inserisci riga sopra",ac:"Elimina riga",ad:"Riga",ae:"Unisci cella sopra",af:"Unisci cella a destra",ag:"Unisci cella sotto",ah:"Unisci cella a sinistra",ai:"Dividi cella verticalmente",aj:"Dividi cella orizzontalmente",ak:"Unisci celle",al:"widget media",am:"Inserisci media",an:"L'URL non può essere vuoto.",ao:"Questo URL di file multimediali non è supportato.",ap:"Caricamento in corso",aq:"Non è stato possibile ottenere l'URL dell'immagine ridimensionata.",ar:"La selezione dell'immagine ridimensionata è fallita",as:"Non è stato possibile inserire l'immagine nella posizione corrente.",at:"L'inserimento dell'immagine è fallito",au:"Cambia testo alternativo dell'immagine",av:"Paragrafo",aw:"Intestazione 1",ax:"Intestazione 2",ay:"Intestazione 3",az:"Editor di testo formattato",ba:"Salva",bb:"Annulla",bc:"Testo alternativo",bd:"Annulla",be:"Ripristina",bf:"URL media",bg:"Paste the URL into the content to embed faster.",bh:"URL del collegamento",bi:"Elimina collegamento",bj:"Modifica collegamento",bk:"Apri collegamento in nuova scheda",bl:"Questo collegamento non ha un URL",bm:"Editor di testo formattato, %0"})})(window.CKEDITOR_TRANSLATIONS||(window.CKEDITOR_TRANSLATIONS={}));

  ClassicEditor.create( document.querySelector( '.document__editable' ),{
        language: 'it',
      })
      .then( editor => {
          const toolbarContainer = document.querySelector( '.document__toolbar' );
          toolbarContainer.appendChild( editor.ui.view.toolbar.element );
          window.editor = editor;
      } )
      .catch( err => {
          console.error( err );
      } );
</script>
@endsection
