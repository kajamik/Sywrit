@extends('front.layout.app')

@section('title', 'Nuova editoria -')

@section('main')
<div class="container">
  <div class="publisher-home">
    <div class="publisher-body">
      <form action="" method="POST">
        @csrf

        <div class="form-group row">
          <label for="name" class="col-md-4 col-form-label text-md-right required" required>Nome Editoria</label>

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
          <label for="_tp" class="col-md-4 col-form-label text-md-right">Tipo restrizione</label>
          <div class="col-md-6">
            <select id="_tp" class="form-control" name="_tp_sel">
              <option value="1">Pubblicazione senza revisione</option>
              <option value="2">Pubblicazione con revisione</option>
            </select>
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
