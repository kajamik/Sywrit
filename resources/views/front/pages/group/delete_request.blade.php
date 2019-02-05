@extends('front.layout.app')

@section('title', $query->nome.' -')

@section('main')
<div class="container">
  <div class="publisher-home">
    <section class="publisher-header" style="background-image: url({{asset($query->getBackground())}})">
      <div class="container">
        <img class="publisher-logo" src="{{asset($query->getLogo())}}" alt="Logo">
        <div class="info">
          <span>{{$query->nome}}</span>
        </div>
      </div>
    </section>
      <section class="publisher-body">
        <li>
          <a href="{{ url($query->slug) }}">Home</a>
        </li>
        <hr/>
        <div class="container my-5">
          <p>Questa pagina verrà disabilitata e verrà eliminata dopo 3 giorni. Procedere con la disabilitazione della pagina ?</p>
          <form action="" method="POST">
            <div class="form-group row">
              <div class="col-md-6">
                <button type="submit" class="btn btn-info">Conferma</button>
              </form>
            </div>
          </form>
        </div>
      </section>
  </div>
</div>
@endsection
