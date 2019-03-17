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
  data() {
    return {
      reply_content: '',
    };
  },
  methods: {
    toReply() {
      $('html, body').animate({scrollTop: $('#hrefReply').offset().top}, 1000);
    },
    collect(event, pid) {
      var $btn = $(event.currentTarget).loading('loading...');

      axios.post("{{route('frontend.post.collect')}}", {
        pid: pid,
      }).then(function (response) {
        return response.data;
      }).then(function (rep) {
        if (rep.status) {
          if (rep.code == -1) {
            login.show = true;
          }
          if (rep.code != 0) {
            window.toast(rep.msg, 'info');
          } else {
            window.toast(rep.msg, 'success');
          }
        } else {
          window.tosat(rep.msg, 'danger');
        }
        
        $btn.loading('reset');
      });
    },
    replyComment(event, pid) {
      var $btn = $(event.currentTarget).loading('loading...');

      axios.post("{{route('frontend.post.comment')}}", {
        pid: pid,
        comment: this.reply_content
      }).then(function (response) {
        return response.data;
      }).then(function (rep) {
        if (rep.status) {
          window.toast(rep.msg, 'success');
          setTimeout(() => {
            window.location.reload();
          }, 1000);
        } else {
          window.toast(rep.msg, 'danger');
        }
        
        $btn.loading('reset');
      });
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
    <a href="javascript:login.show = true;" class="">@lang('frontend.login')</a>
    or
    <a href="javascript:register.show = true;" class="">@lang('frontend.register')</a>
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
          <button type="button" class="btn btn-light btn-sm font12" @click="collect($event, {{$post->id}})">
            <i class="fas fa-star" style="color: #FF9900;"></i> @lang('frontend.collect')
          </button>
        </p>
      </div>
    </div>
  </div>
</div>

{{-- Comments --}}
@foreach ($comments as $comment)
<div class="card mb-3" style="border:none;">
  <div class="card-body">
    <div class="media">
      <figure class="figure mr-3 d-none d-xl-block d-lg-block">
        <figcaption class="figure-caption text-center"></figcaption>
        <img class="align-self-start rounded-circle" src="{{$comment['avatar']}}" width="64">
      </figure>

      <div class="media-body" style="word-wrap:break-word;">
        <h5>{{$comment['name']}}
          <small class="text-black-50 font12">
            @lang('frontend.posts.response_at'): {{date('m/d', strtotime($comment['created_at']))}}
          </small>
        </h5>
        
        <div class="mb-1 border-dashed"></div>
        {{ $comment['content'] }}

        @isset($comment['reply'])
          <div class="mt-3 ml-3 p-1 def-color" style="border: 1px dashed #dee2e6 !important;">
            <i class="fas fa-reply-all"></i> {!! $comment['reply'] !!}
          </div>
        @endisset
      </div>
    </div>
  </div>
</div>
@endforeach
{{-- /Comments --}}

<div class="card mb-3" id="hrefReply" style="border:none;">
  <div class="card-body">
    <div class="media">
      <div class="media-body nowrap">
        <div class="reply-box rounded border">
          @unless (Auth::check())
          <div class="unlogin text-center text-muted">
            @lang('frontend.posts.you_need_to_login_first')
            <a href="javascript:login.show = true" class="">@lang('frontend.login')</a>
            or
            <a href="javascript:register.show = true" class="">@lang('frontend.register')</a>
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
              <textarea class="form-control border-0" v-model.trim="reply_content" style="height: 165px;" placeholder="@lang('frontend.posts.say_something')"></textarea>
              <button @click="replyComment($event, {{$post->id}})" :disabled="reply_content==''" type="button" class="btn btn-info btn-sm float-right text-white"><i class="far fa-paper-plane"></i> @lang('frontend.posts.reply')</button>
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

@login()
@endlogin

@register()
@endregister
@endsection
