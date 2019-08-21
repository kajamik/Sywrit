@extends('front.layout.app')

@section('main')

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
    <div class="publisher-body pt-3">
      <div class="container">

        <div class="col-lg-12">
          <div class="card">
            <a href="{{ url('settings/account') }}">
              <div class="card-body">
                <h5 class="card-title">Modifica informazioni</h4>
                <p>Modifica Nome, Cognome e altre informazioni del tuo profilo</p>
              </div>
            </a>
          </div>
        </div>

        <div class="col-lg-12">
          <div class="card">
            <a href="{{ url('settings/change_password') }}">
              <div class="card-body">
                <h5 class="card-title">Modifica password</h4>
                <p>Modifica la password del tuo account</p>
              </div>
            </a>
          </div>
        </div>

        <div class="col-lg-12">
          <div class="card">
            <a href="{{ url('settings/change_language') }}">
              <div class="card-body">
                <h5 class="card-title">Lingua</h4>
                <p>Modifica la lingua del tuo account</p>
              </div>
            </a>
          </div>
        </div>

      </div>
    </div>
  </div>
@endsection
