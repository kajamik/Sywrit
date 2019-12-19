@extends('front.layout.app')

@section('main')
  @include('front.components.group.top_bar')
  <div class="publisher-content">
    <div class="col-12">
      <div class="row">
        <div class="col-lg-3 col-md-12 p-3 border">
          <ul>
            <li><a href="{{ url('groups/'. $query->id. '/admin') }}"><i class="fa fa-sliders-h"></i> Impostazioni generali</li></a>
            <li><a href="{{ url('groups/'. $query->id. '/admin/users') }}"><i class="fa fa-users"></i> Gestione utenti</li></a>
            <li><a href="{{ url('groups/'. $query->id. '/admin/role') }}"><i class="fa fa-user-shield"></i> Gestione permessi</li></a>
          </ul>
        </div>
        <div class="col-lg-8 col-md-12">
          asd
        </div>
      </div>
    </div>
  </div>
  {{-- close top_bar --}}
</div>
</div>
</div>
@endsection
