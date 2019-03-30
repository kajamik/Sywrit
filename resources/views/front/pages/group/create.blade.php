@extends('front.layout.app')

@section('title', 'Nuova editoria -')

@section('main')
<div class="container">
  <div class="publisher-home">
    <div class="publisher-body">
      <div class="offset-md-1">
        <img src="{{ asset('upload/editoria.png') }}" alt="" />
      </div>
      <form action="" method="POST">
        @csrf

        <div class="form-group row">
          <label for="name" class="col-md-4 col-form-label text-md-right required" required>Nome Redazione</label>

          <div class="col-md-6">
            <input type="text" class="form-control" name="publisher_name">
          </div>
        </div>

        <div class="form-group row">
          <label for="name" class="col-md-4 col-form-label text-md-right">Descrizione</label>

          <div class="col-md-6">
            <input type="text" class="form-control" name="publisher_bio">
          </div>
        </div>

        <div class="form-group row">
          <div class="col-md-6 offset-md-6">
            <button type="submit" class="btn btn-info">Registra editoria</button>
          </div>
        </div>

      </form>
    </div>
  </div>
</div>
@endsection
