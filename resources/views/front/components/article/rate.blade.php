@php
  $request = \DB::table('article_likes')->where('article_id', $query->id);
  $likes = $request->count();
  if(Auth::user()) {
    $liked = $request->where('user_id', Auth::user()->id)->count();
  }
@endphp
<div class="col-12">
  @if(isset($liked) && $liked)
  <i id="reaction" class="bs-icon bs-icon-color-red fa-2x fas fa-heart"></i>
  @else
  <i id="reaction" class="bs-icon fa-2x fas fa-heart" style="color: #D1D1D1"></i>
  @endif
  <span>{{ $likes}}</span>
</div>
