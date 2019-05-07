@extends('front.layout.app')

@section('description', config('app.name'). ': la nuova piattaforma multi-genere di scrittura online.')

@section('title', 'Nuova redazione - ')

@section('main')
  <div class="publisher-home">
    <div class="publisher-body">
      <div class="offset-md-1">
        <img src="{{ asset('upload/redazione.png') }}" alt="" />
      </div>
      <form action="" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group row">
          <label for="name" class="col-md-4 col-form-label text-md-right required">Nome Redazione</label>

          <div class="col-md-6">
            <input type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name">
            @if($errors->has('name'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('name') }}</strong>
                </span>
            @endif
          </div>
        </div>

        <div class="form-group row">
          <label for="name" class="col-md-4 col-form-label text-md-right">Descrizione</label>

          <div class="col-md-6">
            <textarea class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" name="description"></textarea>
            @if($errors->has('description'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('description') }}</strong>
                </span>
            @endif
          </div>
        </div>

        <div class="form-group row">
          <label for="cover" class="col-md-4 col-form-label text-md-right">Immagine di copertina</label>
          <div class="col-md-6">
            <label for="file-upload" class="form-control custom-upload{{ $errors->has('cover') ? ' is-invalid' : '' }}">
              <span class="fa fa-cloud-upload-alt"></span> Carica file
            </label>
            <input id="file-upload" type="file" onchange="App.upload(this.nextElementSibling, false)" name="cover">
            @if($errors->has('cover'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('cover') }}</strong>
                </span>
            @endif
            <div id="preview_cover" class="preview_body"></div>
          </div>
        </div>

        <div class="form-group row">
          <label for="avatar" class="col-md-4 col-form-label text-md-right">Logo</label>
          <div class="col-md-6" >
            <label for="file-upload2" class="form-control custom-upload{{ $errors->has('avatar') ? ' is-invalid' : '' }}">
              <span class="fa fa-cloud-upload-alt"></span> Carica file
            </label>
            <input id="file-upload2" type="file" onchange="App.upload(this.nextElementSibling)" name="avatar">
            @if($errors->has('avatar'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('avatar') }}</strong>
                </span>
            @endif
            <div id="preview_avatar" class="preview_body"></div>
          </div>
        </div>

        <div class="form-group row">
          <div class="col-md-6 offset-md-6">
            <button type="submit" class="btn btn-info">Registra redazione</button>
          </div>
        </div>

      </form>
    </div>
  </div>
@endsection
