@extends('front.layout.app')

@section('main')

<style type="text/css">
#nav > li {
  display: inline-block;
  margin-top: 5px;
  margin-bottom: 10px;
  font-size: 20px;
}
#nav > li:not(:last-child)::after {
  content: '\00a0|';
}
</style>

  <div class="publisher-home">
    <div class="publisher-header" style="background-image: url({{ Auth::user()->getBackground() }})">
      <div class="container">
        <div class="publisher-logo">
          <div class="row">
            <div class="d-inline">
              <img src="{{ Auth::user()->getAvatar() }}" alt="Logo">
            </div>
            <div class="ml-2 mt-2 info">
              <span>
                {!! Auth::user()->getRealName() !!}
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
    <nav class="publisher-nav">
      <ul id="nav" class="row">
        <li><a href="{{ url(Auth::user()->slug) }}">Home</a></li>
        <li><a href="{{ url(Auth::user()->slug.'/about') }}">Informazioni</a></li>
        <li><a href="{{ url(Auth::user()->slug.'/archive') }}">Articoli Salvati</a></li>
      </ul>
    </nav>
      <div class="publisher-body">
        <hr/>
        <h2>Trofei</h2>

        <style>
        .ach {
          padding: 5px 0;
          border: 1px solid #eee;
          border-radius: 3px;
        }
        .ach span {
          color: #bbb;
          font-size: 17px;
        }
        .ach-body {
          margin: 7px 0;
        }
        .ach-footer {
          height: 30px;
          background-color: #000;
          color: #fff;
        }
        .ach-footer > .ach-progress {
          width: 100%;
          height: 100%;
        }
        .ach-footer > .ach-progress > .ach-progress-bar {
          width: 0%;
          height: 100%;
          background-color: green;
        }
        .ach-footer > .ach-progress span {
          position: relative;
          top: -28px;
        }
        .ach-off {
          opacity: 0.5;
        }
        .ach-off > .ach-footer {
          color: #809C8C;
        }
        </style>

        <div class="row">

          @foreach($ach as $value)
          @if(!Auth::user()->achievementStatus($value->id))
          <div class="col-lg-2 col-sm-5">
            <div class="ach text-center">
              <div class="ach-title">@lang($value->name)</div>
              <span>@lang($value->description)</span>
              <div class="ach-body">
                <i class="fa fa-trophy fa-3x"></i>
              </div>
              <div class="ach-footer">
                <div class="ach-progress">
                  <div class="ach-progress-bar" style="width:0%"></div>
                  <span>0% completato</span>
                </div>
              </div>
            </div>
          </div>
          @else
          <div class="col-lg-2 col-sm-5">
            @php
              $progress_value = Auth::user()->Result($value->id)->progress_value;
            @endphp
            <div class="ach @if($progress_value == 100) ach-off @endif text-center">
              <div class="ach-title">@lang($value->name)</div>
              <span>@lang($value->description)</span>
              <div class="ach-body">
                <i class="fa fa-trophy fa-3x"></i>
              </div>
              <div class="ach-footer">
                <div class="ach-progress">
                  <div class="ach-progress-bar" style="width:{{ $progress_value }}%"></div>
                  <span>{{ $progress_value }}% completato</span>
                </div>
              </div>
            </div>
          </div>
          @endif
          @endforeach

          {{-- Auth::user()->achievementStatus(new \App\Achievements\FirstArticle()
            <span class="fa fa-info-circle" data-script="info" data-text="Hai sbloccato questo obbiettivo il giorno 23/06/2019"></span> --}}

        </div>

      </div>
    </div>
@endsection
