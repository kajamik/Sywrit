@extends('front.layout.app')

@section('title', 'Articoli Salvati - ')

@section('main')
<style>
#nav > li {
  display: inline-block;
  margin-top: 5px;
  margin-bottom: 10px;
  font-size: 20px;
}
#nav > li:not(:last-child)::after {
  content: '\00a0|';
}
._ou {
  cursor: pointer;
}
#customMsg {
  min-height: 200px;
}
</style>
        @include('front.components.group.top_bar')
        <div class="publisher-content">
          <h1>Archivio</h1>
          <div class="py-3">
            @if($query2->count())
            <div class="col-lg-12">
              <div class="row" id="articles">
                @foreach($query2->take(12) as $articolo)
                <div class="col-lg-4 col-sm-8 col-xs-12">
                  <a href="{{ url('read/'. $articolo->slug) }}">
                    <div class="card">
                      <img class="card-img-top" src="{{asset($articolo->getBackground())}}" alt="Copertina">
                      <div class="card-body">
                        <h4 class="card-title">{{ $articolo->titolo }}</h4>
                      </div>
                    </div>
                  </a>
                </div>
                @endforeach
              </div>
            </div>
            @else
              <p>Non hai nessun articolo salvato</p>
            @endif
          </div>
        </div>
  </div>
</div>
</div>
<script>
  App.insl('articles');
</script>
@endsection
