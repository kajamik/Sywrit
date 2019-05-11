@extends('front.layout.app')

@php
   SEOMeta::setTitle('Community Standards - Sywrit', false);
 @endphp

@section('main')
  <div class="publisher-home">
    <div class="publisher-body" style="">
      <h1>Sywrit Community Standards</h1>

      <p>Ultima modifica: 17 aprile 2019</strong>
      <p>Le seguenti normative sono finalizzate a garantire la miglior esperienza possibile per ogni utente all' interno di Sywrit.</p>

      <hr/>

      <div id="contents">
        <h2>1) Normative sui contenuti</h2>
        <p>I seguenti contenuti verranno analizzati e, se necessario, saranno presi provvedimenti che variano dalla cancellazione dei post/feedback incriminati, alla sospensione dell'account utente:</p>
        <ul>
          <li>Contenuti che incitano alla violenza e all'odio.</li>
          <li>Contenuti che esprimono crudeltà e insensibilità.</li>
          <li>Contenuti pornografici e relativi ad atti sessuali.</li>
          <li>Contenuti che mirano alla Pubblicizzazione di attività illegali o criminali.</li>
          <li>Contenuti che incitano al suicidio e all'autolesionismo.</li>
          <li>Contenuti riconducibili ad atti di bullismo.</li>
        </ul>
    </div>

    <hr/>

    <div id="authenticity">
      <h2>2) Normative sull'autenticità</h2>
      <p>Al fine di valorizzare l'esperienza di scrittura e incrementare l'affidabilità delle fonti, diamo ad ogni utente la possibilità di recensire, valutare o segnalare ogni articolo, così da limitare:</p>
      <ul>
        <li>Contenuti non conformi alla categoria nella quale sono stati inseriti.</li>
        <li>Diffusione intenzionale di notizie false.</li>
        <li>Rappresentazione di realtà in modo fuorviante o ingannevole.</li>
      </ul>
      <br/>
      <p>Da parte nostra prenderemo provvedimenti che variano dalla cancellazione dei post/feedback incriminati, alla sospensione dell'account utente in caso di:</p>
      <ul>
        <li>Tentativi di spam e repentina pubblicizzazione di un prodotto.</li>
        <li>Creazione di account falsi finalizzati a danneggiare altri membri della community.</li>
        <li>Creazione di account falsi con lo scopo di fingersi un altro utente.</li>
        <li>Pubblicazione di contenuti a nome di altri utenti.</li>
      </ul>
  </div>

  <hr/>

  <div id="intpr">
    <h2>3) Normative sulla proprietà intellettuale</h2>
    <p>Chiediamo di verificare di non aver violato diritti d'autore, copyright, diritti personali, ecc...prima della pubblicazione di un articolo, tenendo presente che al momento della pubblicazione di contenuti e informazioni se ne diventa automaticamente proprietari. Da parte nostra faremo il possibile per tutelare la proprietà intellettuale di ogni membro della community prendendo provvedimenti in caso di violazione di tali diritti.</p>
    <p>Vi Preghiamo infine di collaborare nell'intento di migliorare la qualità della community, segnalando per mezzo dell'apposita funzionalità, ogni presunta violazione del regolamento sopra riportato.</p>

</div>

  </div>

  </div>
@endsection
