@extends('front.layout.app')

@section('title', 'Articoli Salvati -')

@section('main')
@include('front.components.profile.top_bar')
<style>
h2 {
  padding: 8px;
  border-radius: 4px;
  text-transform: uppercase;
  font-size: 23px;
}
h2 + div {
  padding: 15px;
}
address > a, address > a:hover {
  text-decoration: underline;
}
</style>

        <div class="publisher-content">
          <h1>Informazioni utente</h1>
          <div class="py-3">
            @if(!empty($query->biography))
            <h2>Biografia</h2>
            <div class="col-lg-12">
              <p>{!! $query->biography !!}</p>
            </div>
            <hr/>
            @endif
            @if(!empty($query->facebook) || !empty($query->instagram) || !empty($query->linkedin))
            <h2>Socials Link</h2>
            <div class"col-lg-12">
              @if(!empty($query->facebook))
              <address>
                Facebook: <a href="https://facebook.com/{{ $query->facebook }}" target="_blank">{{ $query->facebook }}</a>
              </address>
              @endif
              @if(!empty($query->instagram))
              <address>
                Instagram: <a href="https://instagram.com/{{ $query->instagram }}" target="_blank">{{ $query->instagram }}</a>
              </address>
              @endif
              @if(!empty($query->linkedin))
              <address>
                Linkedin: <a href="https://linkedin.com/in/{{ $query->linkedin }}" target="_blank">{{ $query->linkedin }}</a>
              </address>
              @endif
            </div>
            @else
              <p>Questo utente non ha ancora aggiornato le sue Informazioni</p>
            @endif
          </div>
    </div>
  </div>
</section>
</div>
@endsection
