@extends('front.layout.app')

@section('title', $query->nome.' -')

@section('main')
<style>
.v-card {
  max-height: 15px;
}
.v_card .card .card-img-top {
  padding: 5px;
}
</style>
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
        @if(\Session::get('type') == 'container_right__small')
        <div class="alert-toggle alert alert-danger">
          <h2>{{\Session::get('message')}}</h2>
        </div>
        @endif
        @include('front.components.group.top_bar')
        <div class="container my-5">
          <h2>Informazioni Redazione</h2>
          <div class="col-lg-12">
            <div class="row">
              @foreach(explode(',',$query->componenti) as $value)
              @php
                $user = \App\Models\User::where('id',$value)->first();
                @endphp
                <div class="v_card col-lg-2 col-sm-8 col-xs-12">
                  <a href="{{url($user->slug)}}">
                    <div class="card">
                      <img class="card-img-top" src="{{asset($user->getAvatar())}}" alt="Avatar">
                      <div class="card-body">
                        <strong class="card-title">{{$user->nome}} {{$user->cognome}}</strong>
                        <em>Ruolo:
                          @if($query->direttore == $user->id)
                            Capo Redazione
                          @else
                            Editore
                          @endif
                        </em>
                      </div>
                    </div>
                  </a>
                </div>
              @endforeach
            </div>
          </div>
        </div>
      </section>
  <script>
    App.follow('#follow',
      {
        url:'{{url("follow")}}',
        data:{'id':{{ $query->id }},'mode':'g'}
      },
        false
      );
  </script>
  </div>
</div>
@endsection