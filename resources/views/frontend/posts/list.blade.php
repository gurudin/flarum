@extends('layouts.frontend.app')

@section('title')
{{$category->category}}
@endsection

@section('css')
@endsection

@section('script')
  
@endsection

@section('content')
<div class="card" style="border:none;">
  <div class="card-body">
    <div class="media">
      <img class="align-self-start mr-3 rounded" width="50" src="{{$category->pic}}">
      <div class="media-body">
        <h5 class="mt-0 font15 text-info">{{$category->category}} <small class="text-muted">(@lang('frontend.posts.total_posts'):{{$posts->total()}})</small></h5>
        {{$category->remark}}
      </div>

      <div class="float-right">
        <button type="button" class="btn def-bg-color text-white btn-sm"><i class="far fa-edit"></i> @lang('frontend.posts.topic')</button>
      </div>
    </div>
  </div>
</div>

<div class="container-fluid bg-white mt-2 pt-2 pb-2">
  <button type="button" class="btn btn-outline-info btn-sm">@lang('frontend.posts.latest')</button>
  <button type="button" class="btn btn-outline-info btn-sm">@lang('frontend.posts.hots')</button>
</div>

<div class="control-body mt-2 pl-3 pr-3">
  <div class="card-columns">

    @foreach ($posts as $post)
    <div class="card">
      <a href="{{route('frontend.post', ['id' => $post->id])}}" target="_blank">
        <img class="card-img-top p-1" src={{$post->cover}} alt="{{$post->title}}" title="{{$post->title}}">
      </a>
      <a href="{{route('frontend.post', ['id' => $post->id])}}" target="_blank" class="card-text p-1 d-flex text-secondary" alt="{{$post->title}}" title="{{$post->title}}">
        {{$post->title}}
      </a>
      <p class="card-text text-right p-1">
        <small>
          <span class="p-1 text-muted"><i class="fa fa-eye"></i> {{$post->pv}}</span>
          <span class="p-1 text-muted"><i class="fa fa-coins"></i> {{$post->coin}}</span>
        </small>
      </p>
    </div>
    @endforeach

  </div>

  <div class="mt-3 mb-3 float-right">
    {{ $posts->links() }}
  </div>
</div>

@login(['blade' => $blade])
@endlogin

@register(['blade' => $blade])
@endregister
@endsection
