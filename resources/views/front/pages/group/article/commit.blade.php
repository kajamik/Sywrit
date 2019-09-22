@extends('front.layout.app')

@php
  $article = DB::table('group_article')->where('id', $query->article_id)->first();
  $commited = DB::table('users')->where('id', $query->user_id)->first();
  preg_match_all('/[<*>](.*)[<\/*>]/', $query->old_text, $prec);
  preg_match_all('/[<*>](.*)[<\/*>]/', $query->new_text, $succ);
@endphp

@section('main')
<style>
.bg-commit-strong-plus {
  background-color: #cdffd8;
}
.bg-commit-plus {
  background-color: #e6ffed;
}
.bg-commit-change-plus {
  background-color: #acf2bd;
}

.bg-commit-less {
  background-color: #ffeef0;
}

.bg-commit-strong-less {
  background-color: #ffdce0;
}
</style>
<div class="publisher-home">
  <div class="publisher-body">
      <a href="{{ url('groups/'. $group_id .'/article/'. $article->id .'/commits') }}" title="Back to group">
        <i class="fa fa-2x fa-long-arrow-alt-left"></i>
      </a>

      <div class="card">
        <div class="card-header">
          <h4>{{ $article->title }} - Commit #{{ $query->id }}</h4>
          <span>
            <a href="{{ url($commited->slug) }}">
              {{ $commited->name }} {{ $commited->surname }}
            </a>
            commited {{ $query->created_at->diffForHumans() }}
          </span>
        </div>
        <div class="card-body">

          @php

            $getInfo = getDiff($prec[0], $succ[0]);

          @endphp

          @foreach($getInfo as $n => $value)
          <div class="row">
            <div class="col-1 @isset($value['tag']) @if($value['tag'] == 'remove') bg-commit-strong-less @elseif($value['tag'] == 'add') bg-commit-strong-plus @endif @endisset">{{ $n+1 }}</div>
            <div class="col-11 @isset($value['tag']) @if($value['tag'] == 'remove') bg-commit-less @elseif($value['tag'] == 'add') bg-commit-plus @endif @endisset">
              {!! $value['str'] !!}
            </div>
          </div>
          @endforeach

            {{--
          @foreach($succ[0] as $n => $row)

          @if($row != $prec[0][$n])
            @php
              $array = explode(' ', $row);
              $array2 = explode(' ', $prec[0][$n]);
            @endphp
            <div class="row">
              <div class="col-1 bg-commit-strong-less">{{ $n+1 }}</div>
              <div class="col-11 bg-commit-less">
                @foreach($array2 as $value)
                  {{ strip_tags($value) }}
                @endforeach
              </div>
            </div>
            <div class="row">
              <div class="col-1 bg-commit-strong-plus">{{ $n+1 }}</div>
              <div class="col-11 bg-commit-plus">
              @foreach($array as $p => $value)
                @if($value != $array2[$p])
                  <div class="d-inline bg-commit-change-plus">
                    {{ strip_tags($value) }}
                  </div>
                @else
                  {{ strip_tags($value) }}
                @endif
              @endforeach
              </div>
            </div>
          @else
          <div class="row">
            <div class="col-1">{{ $n+1 }}</div>
            <div class="col-11">
              {{ strip_tags($row) }}
            </div>
          </div>
          @endif

          @endforeach
          --}}


        </div>
      </div>
  </div>
</div>
@endsection
