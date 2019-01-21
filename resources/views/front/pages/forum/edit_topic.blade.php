@extends('front.layout.app')

@section('title','Modifica Topic')

@section('styles')
  <link href="{{ asset('css/forum.css') }}" rel="stylesheet" />
@endsection

@section('main')
<div class="container">
  <div class="col-12 top-background" style="background-image: url({{url('upload/bg-top.png')}})">
    <h3 id="title-shop">Forum</h3>
  </div>
  <div class="board-directory">
    <nav>
      <ul>
        <li><i class="fas fa-home"></i> <a href="{{url('/forum')}}">Indice</a></li>
        <li>{{$category->name}}</li>
        <li><a href="{{url('/forum/'.$section->slug)}}">{{$section->name}}</a></li>
        <li><a href="{{url('/forum/topic/'.$topic->slug)}}">{{$topic->name}}</a></li>
        <li>Modifica Topic</li>
      </ul>
    </nav>
  </div>

  <form method="POST" action="#" aria-label="{{ __('New Topic') }}" id="loginStyle" class="justify-content-center">
    @csrf

    <input type="hidden" name="uri" value="{{__('/forum/topic/'.$topic->slug.'#'.$post->id)}}"/>

    <div class="board-form">

      <div class="board-form-group">
        <label>Titolo</label>
        <input type="text" class="form-control" name="name" value="{{$topic->name}}" required/>
      </div>

      <div class="board-form-group">
        <label>Post</label>
        <textarea rows="20" class="form-control" name="text" required>{{$post->text}}</textarea>
      </div>

      <div class="board-form-group">
        <button class="btn btn-dark" type="submit">
          {{__('Salva Modifiche')}}
        </button>
      </div>

    </div>
  </form>
</div>
@endsection