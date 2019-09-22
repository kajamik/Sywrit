<div class="card">
  <div class="card-body">
    <div class="d-flex">

      <div class="row">
        <img style="height:4em" class="p-2" src="{{ Auth::user()->getAvatar() }}" />
        <div class="col-md-9 col-9">
          <h4><a class="thumbnail" href="{{ url(Auth::user()->slug) }}" data-card-url="/ajax/thumbnail/?id={{ Auth::user()->id }}&h=profile">{!! Auth::user()->getRealName() !!}</a></h4>
          <span>{{ $post->created_at->diffForHumans() }} {{-- $post->memberInfo->tag() --}}</span>
        </div>
        <div class="ml-3 py-3 d-flex col-12">
          {{ $post->text }}
        </div>
      </div>

      <div class="ml-auto">
        <a data-toggle="dropdown" href="#">
          <i class="fas fa-ellipsis-v"></i>
        </a>
        <div class="dropdown-menu">
          <button id="delete_msg_{{ $post->id }}" class="dropdown-item">Elimina</button>
        </div>
      </div>

    </div>

  </div>

  <hr/>

  <div class="col-12">
    <p>0 commenti</p>
  </div>

  <hr/>

  <div class="p-0 col-md-12">
    <div class="d-flex">
      <img style="height:4em" class="p-2 d-none d-md-block" src="{{ Auth::user()->getAvatar() }}" />
      <div class="dialog_box">
        <div class="dialog_editor">
          <div id="d_325" class="dialog_editor__editable" contenteditable="true" role="textarea" data-placeholder="Invia un commento"></div>
        </div>
      </div>
    </div>
  </div>

</div>
