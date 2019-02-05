@section('title', 'Gestione ruoli -')

<h2>Gestione utenti</h2>

<style>
.dropdown-menu {
  width: calc(100% - 30px);
}
._mem {
  margin-top: 15px;
}
</style>

@foreach(explode(',',$query->componenti) as $value)
  @php
    $user = \App\Models\User::where('id',$value)->first();
  @endphp
<div class="_mem">
  <form action="" method="post">
    @csrf
    <div class="col-md-12">
      <li>
        <a data-toggle="dropdown" href="#">
          <button class="dropdown-toggle form-control">{{$user->nome}} {{$user->cognome}}</button>
        </a>
        <div class="dropdown-menu">
          <a class="dropdown-item" href="#">Promuovi</a>
          <a class="dropdown-item" href="#">Congeda</a>
        </div>
      </li>
    </div>
  </form>
</div>
@endforeach
{{--<div class="_mem">
  <div class="col-md-12">
    <a href="#">
      <button class="form-control">Assumi collaboratore <i class="fa fa-plus"></i></button>
    </a>
  </div>--}}
</div>
