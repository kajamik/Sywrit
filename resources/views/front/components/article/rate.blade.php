@php
  $request = \DB::table('article_likes')->where('article_id', $query->id);
  $likes = $request->count();
  $liked = $request->where('user_id', Auth::user()->id)->count();
@endphp
<div id="reaction">
  @if($liked)
  <i class="bs-icon fa-2x fas fa-hand-spock"></i>
  @else
  <i class="bs-icon fa-2x far fa-hand-spock"></i>
  @endif
  <span>{{ $likes }}</span>
</div>
