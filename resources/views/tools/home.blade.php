@extends('tools.layout.app')

@section('title', 'Strumenti -')

@section('main')
<div class="container">
  <h3>Bentornato nel tuo pannello, {{ Auth::user()->nome }}. Cosa vuoi fare oggi ?</h3>
</div>
@endsection
