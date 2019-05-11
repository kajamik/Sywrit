@extends('front.layout.app')

@section('profile::bio')
  @if(!empty($query->biography))
  <h2>Biografia</h2>
  <div class="col-lg-12">
    <p>{!! $query->biography !!}</p>
  </div>
  <hr/>
  @endif
@endsection

@section('main')
  @include('front.components.profile.top_bar')
  <div class="publisher-content">
    <div class="py-3">
      @if($articoli->count())
        <div class="col-lg-12">
          <div class="row" id="articles">
            @foreach($articoli as $value)
            <div class="col-lg-3 col-sm-6 col-xs-12">
              <a href="{{ url('read/'.$value->article_slug)}}">
                <div class="card-header">{{ \Carbon\Carbon::parse($value->published_at)->diffForHumans() }}</div>
                <div class="card border-0">
                  <img class="card-img-top" src="{{asset($value->getBackground())}}" alt="Copertina Articolo">
                  @if($value->topic_id)
                  <span>{{ $value->topic_name }}</span>
                  @endif
                  <h4 class="card-title">{{ $value->article_title }}</h4>
                </div>
              </a>
            </div>
            @endforeach
          </div>
        </div>
        <script>
        App.insl("articles");
        </script>
        @else
        @if(Auth::user() && Auth::user()->id == $query->id)
        <p>Pubblica il tuo primo articolo</p>
        @else
        <p>Questo utente non ha ancora iniziato a pubblicare</p>
        @endif
        <p>
        @endif
      </div>
    </div>
  </div>
</div>
</div>
</div>
@endsection
