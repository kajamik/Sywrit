<div class="card">
  <div class="card-body">
    <div class="d-flex">
      <div class="row">

        <img style="height:4em" class="p-2" src="{{ Auth::user()->getAvatar() }}" />
        <div class="col-9">
          <h4><a href="{{ Auth::user()->slug }}">{!! Auth::user()->getRealName() !!}</a></h4>
          <span>{{ $post->created_at->diffForHumans() }} {!! $post->permission->tag() !!}</span>
          <hr/>
        </div>
        <div class="ml-3 d-flex col-12">
          {{ $post->text }}
        </div>
        {{-- @if(!empty($post->article_id))
        <div class="mt-3 col-12">
        <a href="{{ url('groups/'. $group->id .'/post/'. $value->article_id) }}">
          <div class="card">
            <img class="card-img-top" src="@if($value->cover) {{ $value->cover }} @else {{ asset('upload/no-image.jpg') }} @endif" />
              <div class="card-body">
                <h5 class="card-title" title="{{ $value->article_title }}">{{ str_limit($post->article_title, 33) }}</h5>
              </div>
          </div>
        </a>
        </div>
        @endif --}}

      </div>
    </div>
  </div>
</div>
