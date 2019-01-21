@extends('front.layout.app')

@section('title', $input.' -')

@section('main')
<div class="container">
  <h3>Sono stati trovati {{count($query)+count($query2)}} risultati con la parola '{{$input}}'</h3>
  <div class="col-lg-12">
    <div class="row">
      @foreach($query as $value)
      <div class="col-lg-3 col-sm-8 col-xs-12">
        <a href="#">
          <div class="card">
            <img class="card-img-top" src="" alt="Card image cap">
            <div class="card-body">
              <h4 class="card-title"></h4>
            </div>
          </div>
        </a>
      </div>
      @endforeach
    </div>
  </div>
</div>
@endsection
