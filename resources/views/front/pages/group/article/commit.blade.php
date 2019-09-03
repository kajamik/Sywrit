@extends('front.layout.app')

@php
  $article = DB::table('group_article')->where('id', $query->article_id)->first();
  $commited = DB::table('users')->where('id', $query->user_id)->first();
  preg_match_all('/[<*>](.*)[<\/*>]/', $article->text, $output);
@endphp

@section('main')
<style>
.bg-commit-strong-plus {
  background-color: #cdffd8;
}
.bg-commit-plus {
  background-color: #e6ffed;
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
      <a href="{{ url('groups/'. $query->group_id) }}" title="Back to group">
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
        @foreach($output[0] as $row => $phrase)
        @php
        //->where('row', $row)
          $commit = DB::table('group_article_correction')->where('commit_id', $query->id)->first();
          echo $commit->replaced_word;
        @endphp
        <div class="row">
          <div class="col-1">{{ $row+1 }}</div>
          <div class="col-11">
            {{ strip_tags($phrase) }}
          </div>
        </div>
        {{-- <div class="row">
          <div class="col-1 bg-commit-strong-plus">{{ $row+1 }}</div>
          <div class="col-11 bg-commit-plus">
            {{ $value->new_word }}
          </div>
        </div> --}}
        @endforeach
        </div>
      </div>
  </div>
</div>
@endsection
