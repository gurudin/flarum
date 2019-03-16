@extends('layouts.frontend.app')

@section('title')
{{$isRead == 0 ? $post->title : ''}}
@endsection

@section('css')
@endsection

@section('script')
<script>
const detail = new Vue({
  el: '#detail',
  methods: {
    toReply() {
      $('html, body').animate({scrollTop: $('#hrefReply').offset().top}, 1000);
    }
  }
});
</script>
@endsection

@section('content')
<span id="detail">
@if ($isRead == 1)
  <div class="text-center text-muted m-3 p-3">
    @lang('frontend.posts.it_can_be_read_after_logging_in')
    <a href="{{url(url()->current() . '?blade=login')}}" class="">@lang('frontend.login')</a>
    or
    <a href="{{url(url()->current() . '?blade=register')}}" class="">@lang('frontend.register')</a>
  </div>
@elseif ($isRead == 2)
  <div class="text-center text-muted m-3 p-3">
    @lang('frontend.posts.become_a_vip_can_view'), 
    <a href="javascript:alert('Temporarily not opened. \n 暂未开放.');">
      @lang('frontend.posts.click_to_become_a_member')
    </a>
  </div>
@elseif ($isRead == 3)
  <div class="text-center text-muted m-3 p-3">
    @lang('frontend.posts.coin_shortage'), 
    <a href="javascript:alert('Temporarily not opened. \n 暂未开放.');">
      @lang('frontend.posts.click_recharge_coin')
    </a>
  </div>
@elseif ($isRead == -1)
  <div class="text-center text-muted m-3 p-3">
    @lang('frontend.posts.please_refresh_and_try_again')
  </div>
@else
{{-- normal --}}
<div class="card" style="border:none;">
  <div class="card-body">
    <div class="media">
      <img class="align-self-start mr-3 rounded" width="50" src="{{$category->pic}}">
      <div class="media-body">
        <h5 class="mt-0 font15 text-info">{{$category->category}}</h5>
        {{$category->remark}}
      </div>

      <div class="float-right">
        <button type="button" @click="toReply" class="btn def-bg-color text-white btn-sm">
          <i class="far fa-edit"></i> @lang('frontend.posts.reply')
        </button>
      </div>
    </div>
  </div>
</div>

<div class="card mt-3 mb-3" style="border:none;">
  <div class="card-body">
    <div class="media">
      <figure class="figure mr-3 d-none d-xl-block d-lg-block">
        <figcaption class="figure-caption text-center"></figcaption>
        <img class="align-self-start rounded-circle" src="{{$from->avatar}}" width="64">
      </figure>

      <div class="media-body nowrap">
        <h5 class="nowrap">{{$post->title}}</h5>
        <div class="text-black-50 font12">
          @lang('frontend.posts.author'): {{$from->nickname}}
          <span class="separator ml-2 mr-2">|</span>
          @lang('frontend.posts.created_at'): {{date('m/d', strtotime($post->updated_at))}}
        </div>
        <div class="mb-1 border-dashed"></div>
        
        {!! $post->content !!}

        <p class="text-center">
          <button type="button" class="btn btn-light btn-sm font12"><i class="fas fa-star" style="color: #FF9900;"></i> 收藏</button>
        </p>
      </div>
    </div>
  </div>
</div>

{{-- Comments --}}
<div class="card mb-3" style="border:none;">
  <div class="card-body">
    <div class="media">
      <figure class="figure mr-3 d-none d-xl-block d-lg-block">
        <figcaption class="figure-caption text-center"></figcaption>
        <img class="align-self-start rounded-circle" src="{{$from->avatar}}" width="64">
      </figure>

      <div class="media-body" style="word-wrap:break-word;">
        <h5>admin
          <small class="text-black-50 font12">
            @lang('frontend.posts.response_at'): 03/01
          </small>
        </h5>
        
        <div class="mb-1 border-dashed"></div>
        111asd
        {{-- {!! $post->content !!} --}}
      </div>
    </div>
  </div>
</div>
{{-- /Comments --}}

<div class="card mb-3" id="hrefReply" style="border:none;">
  <div class="card-body">
    <div class="media">
      <div class="media-body nowrap">
        <div class="reply-box rounded border">
          @unless (Auth::check())
          <div class="unlogin text-center text-muted">
            @lang('frontend.posts.you_need_to_login_first')
            <a href="{{url(url()->current() . '?blade=login')}}" class="">@lang('frontend.login')</a>
            or
            <a href="{{url(url()->current() . '?blade=register')}}" class="">@lang('frontend.register')</a>
          </div>
          @endunless

          @auth
          <div class="media p-3">
            <img class="align-self-start mr-3 rounded-circle d-none d-xl-block d-lg-block" src="{{Auth::user()->avatar}}" width="50">
            <div class="media-body nowrap">
              <h5 class="mt-0 nowrap">
                <img class="mr-1 float-left rounded-circle d-block d-xl-none d-lg-none" src="{{Auth::user()->avatar}}" width="25">
                <i class="fas fa-reply"></i> {{$post->title}}
              </h5>
              <textarea class="form-control border-0" style="height: 165px;" placeholder="说点什么..."></textarea>
              <button type="button" class="btn btn-info btn-sm float-right text-white"><i class="far fa-paper-plane"></i> 回复</button>
            </div>
          </div>
          @endauth
        </div>
      </div>
    </div>
  </div>
</div>
@endif
</span>

@login(['blade' => $blade])
@endlogin

@register(['blade' => $blade])
@endregister
@endsection
