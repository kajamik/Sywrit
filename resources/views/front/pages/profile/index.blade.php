@extends('front.layout.app')

@section('title', $query->nome.' '.$query->cognome.' -')

@section('main')
<div class="container">
  <div class="publisher-home">
    <section class="publisher-header" style="background-image: url({{asset($query->getBackground())}})">
      <div class="container">
        <img class="publisher-logo" src="{{asset($query->getAvatar())}}" alt="Logo">
        <div class="info">
          <span>{{$query->nome}} {{$query->cognome}}</span>
        </div>
      </div>
    </section>
    <section class="publisher-body">
      <div class="container">
        @if($query->id_gruppo == 0)
          <p>Editore individuale</p>
        @else
          <p>Editore presso <a href="{{ url('group/'.$group->slug) }}">{{$group->nome}}</a></p>
        @endif
        @if(\Auth::user() && \Auth::user()->id != $query->id)
        <div class="publisher-info">
          <button class="btn btn-primary"><i class="fas fa-envelope"></i> <span>Invia messaggio</span></button>
          @if(!$follow)
            <button id="follow" class="btn btn-primary"><i class="fas fa-bell"></i> <span>Segui</span></button>
          @else
            <button id="follow" class="btn btn-primary"><i class="fas fa-bell-slash"></i> <span>Smetti di seguire</span></button>
          @endif
        </div>
        @endif
        <div class="publisher-bar" data-pub-text="#followers">
            <i class="fa fa-newspaper" title="{{$count}} Articoli"></i> <span>{{$count}}</span>
            <i class="fab fa-angellist" title="{{$query->followers_count}} Followers"></i> <span id="followers">{{$query->followers_count}}</span>
        </div>
        @if(!empty($query->biography))
        <div class="py-4">
          <h4>Biografia</h4>
          <p>{!! $query->biography !!}</p>
        </div>
        @endif
        <hr/>
        <div class="publisher-content">
          <div class="py-3">
            @if($count)
            <div class="col-lg-12">
              <div class="row" id="articles">
                @foreach($articoli->take(12) as $n => $articolo)
                @php
                  if(!$n)
                    $original_image = \App\Models\BackupArticlesImages::where('article_id',$articolo->id)->first();
                @endphp
                <div class="@if($n > 0) col-lg-4 @endif col-sm-8 col-xs-12">
                  <a href="{{ url('read/'. $articolo->slug) }}">
                    <div class="card" title="{{$n}}">
                      @if($articolo->copertina)
                        @if(!$n)
                          <img class="card-img-top" src="{{asset('storage/articles/'.$original_image->img_title)}}" alt="Copertina">
                        @else
                          <img class="card-img-top" src="{{asset('storage/articles/'.$articolo->copertina)}}" alt="Copertina">
                        @endif
                      @endif
                      <div class="card-body">
                        <h4 class="card-title">{{ $articolo->titolo }}</h4>
                      </div>
                    </div>
                  </a>
                </div>
                @endforeach
              </div>
            </div>
            @else
              <p>Questo utente non ha ancora iniziato a pubblicare</p>
            @endif
          </div>
        </div>
    </div>
  </div>
</section>
  <script>
    //App.loadData('#articles','?page=');
    App.follow('#follow',
    {
      url:'{{url("follow")}}',
      data:{'id': {{ $query->id }}, 'mode': 'i'}
      }, false);
    App.insl('articles');
  </script>
</div>
@endsection
