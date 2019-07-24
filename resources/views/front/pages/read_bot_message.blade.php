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
      <div class="row">
        <div class="col-lg-9 col-md-9">
          <article class="block-article">
            <div class="block-title">
              <h1 class="text-uppercase">{{ $query->titolo }}</h1>
            </div>
            <p>Articolo generato dal sistema</p>
            <div class="date-info">
              <span class="date"><i class="far fa-calendar-alt"></i> {{ $date }}</span>
              <span class="time"><i class="far fa-clock"></i> {{ $time }}</span><br/>
            </div>
            <hr/>
            <div class="block-body">
              {!! $query->testo !!}
            </div>
            <hr style="border-style:dotted"/>
            <div class="both"></div>
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
            <div class="row pt-5">
              <div class="socials">
                <div class="col-lg-12 col-sm-12 col-xs-12">
                  <a id="share" href="#share">
                    <span class="fa-2x fa fa-share-square"></span>
                  </a>
                </div>
                <script>
                  App.share({
                    'apps': [
                      'facebook', 'linkedin'
                    ],
                    'appendTo': '#share',
                  });
                </script>
              </div>
        </div>
      </article>
    </div>
    <div class="col-lg-3 col-md-3">
      <div class="position-sticky sticky-top" style="top:63px">
        <div class="card">
          <div class="card-header bg-sw">
            Simon
            <div class="sw-ico">
              <i class="fa fa-robot" title="Sono un bot!! &#xA;I profili con questa icona sono generati in modo automatico"></i>
            </div>
          </div>
          <div class="card-body">

            <div class="text-center">

              <img src="{{ asset('upload/_bot.jpg') }}" alt="Avatar di Sywbot" />

              <h4>Autore</h4>

              <hr/>

              <h5>Biografia:</h5>
              <p>Sono il bot generato dal sistema</p>

              <a href="https://facebook.com/sywrit" target="_blank" title="Facebook">
                <i class="fab fa-facebook fa-2x"></i>
              </a>
              <a href="https://instagram.com/sywrit" target="_blank" title="Instagram">
                <i class="fab fa-instagram fa-2x"></i>
              </a>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>

  </div>
  </div>
@endsection
