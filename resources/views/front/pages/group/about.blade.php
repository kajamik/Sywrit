@extends('front.layout.app')

@section('title',  $query->name.' - ')

@section('main')
<style>
.v-card {
  max-height: 15px;
}
.v_card .card .card-img-top {
  padding: 5px;
}
</style>
<div class="publisher-home">
        @include('front.components.group.top_bar')
        <div class="container my-5">
          @if($query->biography)
          <p>{!! $query->biography !!}</p>
          @endif
          <h2>Informazioni Redazione</h2>
          <div class="col-lg-12">
            <div class="row">
              @foreach($components as $value)
              @php
                $user = \App\Models\User::where('id',$value)->first();
                @endphp
                <div class="v_card col-lg-2 col-sm-8 col-xs-12">
                  <a href="{{url($user->slug)}}">
                    <div class="card">
                      <img class="card-img-top" src="{{asset($user->getAvatar())}}" alt="Avatar">
                      <div class="card-body">
                        <strong class="card-title">{{ $user->name }} {{ $user->surname }}</strong>
                        <em>Ruolo:
                          @if($query->direttore == $user->id)
                            Responsabile
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
@endsection
