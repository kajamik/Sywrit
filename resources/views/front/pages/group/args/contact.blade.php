@include('front.components.group.top_bar')

@php
  $info = \DB::table('Utenti')->where('id',$query->direttore)->first();
@endphp
