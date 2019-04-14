@section('title', 'Gestione ruoli redazione -')

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
            <strong class="card-title">{{$user->nome}} {{$user->cognome}}</strong>
            <em>Ruolo:
              @if($query->direttore == $user->id)
                Redattore
              @else
                Editore
              @endif
            </em>
            @if($user->id != \Auth::user()->id)
            <hr/>
            <a id="prom_{{$user->id}}" href="#promóvida">Rendi redattore</a>
            <a id="lamp_{{$user->id}}" href="#lampàre">Congeda</a>

            <script type="text/javascript">
            document.getElementById("prom_{{$user->id}}").addEventListener('click',function(){App.getUserInterface({
              "ui": {
                "title": 'Avviso',
                "header": {"action": "{{route('group/user/promote')}}","method": "POST"},
                "data": {"id": "{{ $user->id }}", "_token": "{{ csrf_token() }}"},
                "content": [
                    {"type": ["h5"], "label": "Vuoi davvero rendere questo utente redattore? Così facendo assolverai le tue attuali funzioni da redattore."},
                    {"type": ["button","submit"], "class": "btn btn-info btn-block", "text": "Affida incarico"}
                  ]
              }
            });
            });
            document.getElementById("lamp_{{$user->id}}").addEventListener('click',function(){
              App.getUserInterface({
              "ui": {
                "title": 'Avviso',
                "header": {"action": "{{route('group/user/fired')}}","method": "POST"},
                "data": {"id": "{{ $user->id }}", "_token": "{{ csrf_token() }}"},
                "content": [
                    {"type": ["h5"], "label": "Vuoi davvero congedare {{ $user->nome }} {{ $user->surname }}?"},
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
    {{--<div id="lth" class="v_card col-lg-2 col-sm-8 col-xs-12">
      <div class="card">
        <img class="card-img-top" src="{{asset('upload/new_user.png')}}" alt="Nuovo Utente">
        <div class="card-body">
          <p class="card-title">Aggiungi nuovo utente</p>
        </div>
    </div>
  </div>--}}
</div>
{{--
<script type="text/javascript">
document.getElementById("lth").addEventListener('click',function(){
  App.getUserInterface({
  "ui": {
    "title": 'Assumi collaboratore',
    "header": {"action": "{{route('group/user/invite')}}","method": "POST"},
    "data": {"id": "{{ $user ->id }}", "_token": "{{ csrf_token() }}"},
    "content": [
        {"type": ["input","email"], "class": "form-control", "name": "select", "placeholder": "Inserisci indirizzo email", "required": true},
        {"type": ["button","submit"], "class": "btn btn-info btn-block", "text": "Invia richiesta"}
      ],
    "done": {
      "ui": {
        "title": "Info Redazione",
        "content": [
          {"type": ["h5"], "text": "Richiesta inviata. Aspetta che accetti"},
        ]
      }
    }
  }
});
});
</script>
--}}
