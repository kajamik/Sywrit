@extends('front.layout.app')

@section('main')
<div class="publisher-home">
  <div class="publisher-body">
      <a href="{{ url('groups/'. $group_id .'/article/'. $article->id) }}" title="Back to group">
        <i class="fa fa-2x fa-long-arrow-alt-left"></i>
      </a>

      <h4>{{ $article->title }}</h4>

      @foreach($query as $value)
      @php
        $commited = DB::table('users')->where('id', $value->user_id)->first();
      @endphp
      <div class="card">
        <div class="card-body">
          <a href="{{ url('groups/'. $group_id .'/article/'. $article->id .'/commit/'. $value->id) }}">
            <p>Correzione effettuata da {{ $commited->name }} {{ $commited->surname }}</p>
            <medium>{{ $value->created_at->diffForHumans() }}</medium><br>
            <medium>Note: {{ $value->note }}</medium>
          </a>
        </div>
      </div>
      @endforeach
  </div>
</div>
@endsection
