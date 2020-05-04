@extends('front.layout.app')

@section('main')

  <div class="publisher-home">
    <div class="publisher-body">

      <div class="block-hero">
        <div class="row">
          <div class="caption col-lg-10">
            <div class="block-body">
              <p>Sywrit è una piattaforma <strong>interamente gratuita</strong> in cui poter condividere le proprie idee, passioni e molto altro.</p>
              <p>Entra a fare parte della nostra community e inizia a pubblicare i tuoi articoli. <a class="text-info" href="{{ url('page/about') }}" target="_blank">Per saperne di più.</a></p>
            </div>
          </div>
          <div class="d-none d-lg-block pt-3 ml-5">
            <svg width="96" height="96" viewBox="0 -5 109 140">
              <g>
                <path style="fill:#c7bde5;stroke-width:0.76800001" d="M 30.908444,94.26849 C 13.9088,77.264955 0,63.007355 0,62.584932 0,61.585674 61.592629,0 62.592,0 c 1.003932,0 62.592,61.588068 62.592,62.592 0,1.008492 -61.590462,62.592 -62.599068,62.592 -0.422424,0 -14.676843,-13.91198 -31.676488,-30.91551 z" />
                <path
                  style="fill:#000000;stroke-width:0.76800001" d="M 30.908444,94.26849 C 13.9088,77.264955 0,63.007355 0,62.584932 0,61.585674 61.592629,0 62.592,0 c 1.003932,0 62.592,61.588068 62.592,62.592 0,1.008492 -61.590462,62.592 -62.599068,62.592 -0.422424,0 -14.676843,-13.91198 -31.676488,-30.91551 z M 91.20384,33.98779 62.59963,5.3759498 33.98779,33.98016 5.3759498,62.58437 33.98016,91.196214 62.58437,119.80805 91.196214,91.20384 119.80805,62.59963 Z M 50.198045,97.550254 C 40.148154,94.91553 33.089504,90.207245 34.155262,86.849341 c 0.733275,-2.310343 2.254037,-1.99352 6.773691,1.411185 9.106095,6.859707 24.35982,9.406026 24.348176,4.064456 C 65.272376,90.144637 62.801464,86.823713 54.583003,77.952 34.269059,56.023378 30.999086,49.409026 35.598988,39.552 41.436551,27.042821 59.929495,21.386515 77.351363,26.781497 c 7.116664,2.203798 10.33986,4.41676 10.051077,6.900801 -0.347536,2.989355 -2.464666,2.916032 -7.053297,-0.244275 C 72.414634,27.973314 62.087684,26.639749 59.86503,30.792816 58.380657,33.566394 61.212364,37.52047 74.115523,50.691597 88.868436,65.750895 90.986051,69.128838 90.96414,77.568 c -0.0094,3.606735 -0.472029,5.864648 -1.667298,8.136714 -5.714488,10.862538 -22.940956,16.081546 -39.098797,11.84554 z" />
              </g>
            </svg>
          </div>
        </div>
      </div>

      @if(Session::has('alert'))
      <div class="alert alert-{{ Session::get('alert') }}" role="alert">
        @if(Session::has('date'))
        <i class="fa fa-check"></i> L'articolo sarà pubblicato {!! Session::get('date') !!} alle ore {!! Session::get('time') !!}
        @else
        <i class="fa fa-check"></i> {!! Session::get('msg') !!}
        @endisset
      </div>
      @endif

      <hr style="background-color:#fefeff;"/>

      <h4>Tutti gli articoli</h4>

      <div class="row">
        <div class="col-lg-9 col-md-7 col-sm-12">

          {{-- Solo per gli utenti che non hanno ancora pubblicato un articolo --}}
          @if(Auth::user() && Auth::user()->getPublications()->count() == 0)
          <div class="d-flex bg-sw p-2 mb-3">
            <a href="{{ url('write') }}">
              @lang('label.notice.first_article')
            </a>
          </div>
          @endif

          @if($articoli->count())
          <div class="row" id="news">
            @foreach($articoli as $value)
            <div class="col-lg-4 col-sm-12 col-xs-12">
            <a href="{{ url('read/'.$value->article_slug) }}">
              <div class="card-header">
                {{ \Carbon\Carbon::parse($value->created_at)->diffForHumans() }}
              </div>
              <div class="card">
                <img class="card-img-top" src="{{ $value->getBackground() }}" alt="{{ $value->article_title }}" />
                  <div class="card-body">
                    @if($value->topic_id)
                      <span>{{ $value->topic_name }}</span>
                    @endif
                    <h5 class="card-title" title="{{ $value->article_title }}">{{ str_limit($value->article_title, 33) }}</h5>
                    {{--
                      <p>{!! str_limit(preg_replace('/(<.*?>)|(&.*?;)/', '', $value->article_text), 100) !!}</p>
                    --}}
                    @if($value->bot_message == '1')
                    <p>Messaggio generato dal sistema</p>
                    @else
                    <div class="author">
                      @if($value->id_editore)
                        @lang('label.article.published_by', ['name' => $value->publisher_name, 'url' => url($value->publisher_slug)])
                      @else
                        @lang('label.article.published_by', ['name' => $value->user_name.' '.$value->user_surname, 'url' => url($value->user_slug)])
                      @endif
                    </div>
                    @endif
                  </div>
              </div>
            </a>
          </div>
          @endforeach
        </div>
        @else
        <div class="col-lg-4 col-sm-12 col-xs-12">
          <a href="{{ url('write')}}">
            <div class="card">
              <div class="card-header">@lang('label.notice.new_article')</div>
              <img class="card-img-top" src="{{ asset('upload/no-image.jpg') }}" alt="Crea nuovo articolo">
            </div>
          </a>
        </div>
        @endif
        </div>

        {{-- Menù laterale --}}
        <div class="col-lg-3 col-md-5 col-sm-12 d-md-block d-none">
          <div class="sw-lnav sticky-top position-sticky" style="top:80px">
            {{--<div class="sw-component">
              <div class="sw-component-header bg-sw">@lang('label.popul_articles')</div>
                @foreach($popular_articles as $value)
                <a href="{{ url('read/'.$value->article_slug) }}">
                  <div class="sw-item" style="background-image: url('{{ $value->getBackground() }}');background-repeat: no-repeat;background-size:100%;background-size:contain;">
                    <div class="sw-item-header">
                      {!! str_limit(preg_replace('/(<.*?>)|(&.*?;)/', '', $value->article_text), 100) !!}
                    </div>
                    <div class="sw-item-footer">
                      Di
                      @if($value->id_editore)
                      <a href="{{ url($value->publisher_slug) }}">
                        <span>{{ $value->publisher_name }}</span>
                      </a>
                      @else
                      <a href="{{ url($value->user_slug) }}">
                        <span>{{ $value->user_name }} {{ $value->user_surname }}</span>
                      </a>
                      @endif
                    </div>
                  </div>
                </a>
                @endforeach
            </div>--}}
            <div class="sw-component">
              <div class="sw-component-header bg-sw">@lang('label.follow_us_on')</div>
              <div class="sw-item text-center">
                <div class="sw-icon-large">
                  <a href="https://facebook.com/sywrit" target="_blank">
                    <i class="fab fa-facebook"></i>
                  </a>
                </div>
                <div class="sw-icon-large">
                  <a href="https://instagram.com/sywrit" target="_blank">
                    <i class="fab fa-instagram"></i>
                  </a>
                </div>
                <div class="sw-item">
                    <div class="links d-inline">
                      <a href="{{ url('page/about/privacy') }}">@lang('label.footer.privacy')</a>
                      <a href="{{ url('page/legal/terms') }}">@lang('label.footer.terms')</a>
                      <a href="{{ url('page/about') }}">@lang('Informazioni')</a>
                    </div>
                    <p>&copy; {{ \Carbon\Carbon::now()->format('Y') }} Sywrit</p>
                </div>
              </div>
            </div>
          </div>
        </div>

      <script>
        App.insl('news');
      </script>

    </div>
</div>

@endsection
