@extends('front.layout.app')

@section('title', 'Nuovo articolo -')

@section('main')
<style type="text/css">
#header {z-index:999}
.document {
    max-height: 700px;
    display: flex;
    flex-flow: column nowrap;
    border: .6px solid #bbb;
}
.document__toolbar {
    box-shadow: 0 0 5px hsla( 0,0%,0%,.2 );
    border-bottom: 1px solid var(--ck-color-toolbar-border);
}
.document__toolbar .ck-toolbar {
    border: 0;
    border-radius: 0;
}
.document__editable-container {
    background: #e6e6e6;
    overflow-y: scroll;
    overflow-x: hidden;
}
.document__editable-container .ck-editor__main {
    padding: .9cm .2cm .9cm .2cm;
}
.document__editable-container .ck-editor__editable {
    width: 23cm;
    max-width: 100%;
    min-height: 21cm;
    padding: 1cm 1cm 1cm;
    border: 1px hsl( 0,0%,82.7% ) solid;
    border-radius: var(--ck-border-radius);
    background: white;
    box-shadow: 0 0 5px hsla( 0,0%,0%,.1 );
    margin: 0 auto;
}
//
.document .ck-content,
.document .ck-heading-dropdown .ck-list .ck-button__label {
    font: 16px/1.6 "Helvetica Neue", Helvetica, Arial, sans-serif;
}
.document .ck-heading-dropdown .ck-list .ck-button__label {
    line-height: calc( 1.7 * var(--ck-line-height-base) * var(--ck-font-size-base) );
    min-width: 6em;
}
.document .ck-heading-dropdown .ck-list .ck-button:not(.ck-heading_paragraph) .ck-button__label {
    transform: scale(0.8);
    transform-origin: left;
}
.document .ck-content h2,
.document .ck-heading-dropdown .ck-heading_heading1 .ck-button__label {
    font-size: 2.18em;
    font-weight: normal;
}

.document .ck-content h2 {
    line-height: 1.37em;
    padding-top: .342em;
    margin-bottom: .142em;
}
//
.document .ck-content h3,
.document .ck-heading-dropdown .ck-heading_heading2 .ck-button__label {
    font-size: 1.75em;
    font-weight: normal;
    color: hsl( 203, 100%, 50% );
}
.document .ck-heading-dropdown .ck-heading_heading2.ck-on .ck-button__label {
    color: var(--ck-color-list-button-on-text);
}
.document .ck-content h3 {
    line-height: 1.86em;
    padding-top: .171em;
    margin-bottom: .357em;
}
.document .ck-content h4,
.document .ck-heading-dropdown .ck-heading_heading3 .ck-button__label {
    font-size: 1.31em;
    font-weight: bold;
}
.document .ck-content h4 {
    line-height: 1.24em;
    padding-top: .286em;
    margin-bottom: .952em;
}
.document .ck-content p {
    font-size: 1em;
    line-height: 1.63em;
    padding-top: .5em;
    margin-bottom: 1.13em;
}
.document .ck-content blockquote {
    font-family: Georgia, serif;
    margin-left: calc( 2 * var(--ck-spacing-large) );
    margin-right: calc( 2 * var(--ck-spacing-large) );
}
</style>
<div class="container">
  <form method="post" action="">
    @csrf

  <div class="mt-5">
    <div class="form-group row">
        <div class="col-md-12">
            <input id="title" type="text" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" name="title" placeholder="Titolo" required autofocus>
            @if ($errors->has('title'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('title') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group row">
        <div class="col-md-12">
          <div class="document">
              <div class="document__toolbar"></div>
              <div class="document__editable-container">
                  <textarea class="document__editable" name="document__text"></textarea>
              </div>
          </div>
        </div>
    </div>

    <div class="form-group row">
        <div class="col-md-2 offset-md-2">
            <button type="submit" class="btn btn-primary">
                {{ __('Pubblica') }}
            </button>
        </div>
    </div>

  </form>
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
