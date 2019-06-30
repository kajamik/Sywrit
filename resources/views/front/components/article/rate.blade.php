  @php
    $score = \DB::table('article_score')->where('article_id', $query->id);
  @endphp
  <span id="rcount" class="pr-3">{{ number_format($score->sum('score') / $score->count(), 2) }} / 5</span>
  <div class="rating">
    @for($i = 0; $i < 5; $i++)
      @if( $score->sum('score') / $score->count() > $i)
        @if( floor($score->sum('score') / $score->count()) > $i)
        <span class="circle full"></span>
        @else
        <span class="circle half"></span>
        @endif
      @else
        <span class="circle"></span>
      @endif
    @endfor
  </div>
