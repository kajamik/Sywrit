@extends('front.layout.app')

@section('main')
<div class="container">
  Lista Editori
  @if(!empty($query))
  <div class="col-lg-12">
    <div class="row">
      @foreach($query as $value)
          <div class="col-lg-2 col-sm-6 col-xs-12">
            <a href="{{ url('read/'.$articolo->id.'-'.$articolo->slug)}}">
              <div class="card">
                <img class="card-img-top" src="{{asset($value->getLogo())}}" alt="Card image cap">
                <div class="card-body">
                  <h5 class="card-title">{{ $value->nome }}</h5>
                </div>
              </div>
            </a>
          </div>
          @endforeach
        </div>
      </div>
    @endif
</div>
@endsection
