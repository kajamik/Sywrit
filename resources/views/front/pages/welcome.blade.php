@extends('front.layout.app')

@section('main')

  <div class="publisher-home">
    <div class="publisher-body">

      <div class="block-hero">
        <div class="caption">
          <div class="block-body">
            <p>Entra nella community e inizia a pubblicare i tuoi articoli.</p>
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
          <div class="sw-component">
            <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<ins class="adsbygoogle"
     style="display:block"
     data-ad-test="on"
     data-ad-format="fluid"
     data-ad-layout-key="-bc+bx-47-9q+xs"
     data-ad-client="ca-pub-6905675700225112"
     data-ad-slot="4440509908"></ins>
<script>
     (adsbygoogle = window.adsbygoogle || []).push({});
</script>
          </div>
        </div>

      <script>
        App.insl('news');
      </script>

    </div>
</div>

@endsection
