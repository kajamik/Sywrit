@php
  $info = \App\User::where('id',$query->direttore)->first();
@endphp
<div class="col-lg-12">
    <p>Direttore: {{ $info->nome }}</p>
    <p>Indirizzo email: [anti-robot]</p>
</div>
