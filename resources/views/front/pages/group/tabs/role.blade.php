@section('title', 'Gestione ruoli redazione - ')

<h2>Gestione utenti</h2>

<style>
.dropdown-menu {
  width: calc(100% - 30px);
}
._mem {
  margin-top: 15px;
}
</style>

<div class="col-lg-12">
  <div class="row">
    @php
      $components = collect(explode(',',$query->componenti))->filter(function ($value, $key) {
        return $value != "";
      });
    @endphp
  @foreach($components as $value)
    @php
      $user = \App\Models\User::where('id',$value)->first();
      @endphp
      <div id="usr_{{$user->id}}" class="v_card col-lg-2 col-sm-8 col-xs-12">
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
            @if($user->id != \Auth::user()->id)
            <hr/>
            <a id="prom_{{$user->id}}" href="#promóvida">Promuovi</a>
            <a id="lamp_{{$user->id}}" href="#lampàre">Congeda</a>

            <script type="text/javascript">
            document.getElementById("prom_{{$user->id}}").addEventListener('click',function(){App.getUserInterface({
              "ui": {
                "title": 'Avviso',
                "header": {"action": "{{ route('group/user/promote') }}","method": "POST"},
                "data": {"id": "{{ $user->id }}", "publisher_id": "{{ $query->id }}", "_token": "{{ csrf_token() }}"},
                "content": [
                    {"type": ["h5"], "label": "Vuoi nominare {{ $user->name }} {{ $user->surname }} nuovo responsabile di questa redazione?"},
                    {"type": ["button","submit"], "class": "btn btn-info btn-block", "text": "Conferma"}
                  ],
                  "done": function(){
                    window.location.reload(false);
                  }
              }
            });
            });
            document.getElementById("lamp_{{$user->id}}").addEventListener('click',function(){
              App.getUserInterface({
              "ui": {
                "title": 'Avviso',
                "header": {"action": "{{ route('group/user/fired') }}","method": "POST"},
                "data": {"id": "{{ $user->id }}", "publisher_id": "{{ $query->id }}", "_token": "{{ csrf_token() }}"},
                "content": [
                    {"type": ["h5"], "label": "Vuoi espellere {{ $user->name }} {{ $user->surname }} dalla redazione?"},
                    {"type": ["button","submit"], "class": "btn btn-info btn-block", "text": "Conferma"}
                  ],
                  "done": function(){
                    App.getUserInterface({
                      "ui": {
                        "title": "Info Redazione",
                        "content": [
                          {"type": ["h5"], "text": "Questo utente è stato rimosso"}
                        ]
                      }
                    });
                    $("#usr_{{$user->id}}").fadeOut();
                  }
              }
            });
            });
            </script>
            @endif
            </div>
          </div>
      </div>
    @endforeach
</div>
