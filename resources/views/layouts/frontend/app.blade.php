<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>@yield('title') {{ config('app.name', '') }}</title>

  <!-- Styles -->
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
  <link href="{{ asset('css/frontend.css') }}" rel="stylesheet">
  
  <script src="{{ asset('js/app.js') }}"></script>
  
  <style>
    .gurudin-toast {
      position: absolute;
      cursor: pointer;
      top: 10px;
      right: 10px;
      padding: 15px 15px 15px 50px;
      min-width: 250px;
      -moz-border-radius: 3px;
      -webkit-border-radius: 3px;
      border-radius: 3px;
      background-position: 15px center;
      background-repeat: no-repeat;
      -moz-box-shadow: 0 0 12px #999;
      -webkit-box-shadow: 0 0 12px #999;
      box-shadow: 0 0 12px #999;
      color: #fff;
      opacity: .8;
      -ms-filter: progid:DXImageTransform.Microsoft.Alpha(Opacity=80);
      filter: alpha(opacity=80);
      z-index: 99999;
    }
    .gurudin-toast:hover {
      opacity: 1;
    }
    .gurudin-toast i {
      font-size: 25px;
      margin-right: 10px;
      position: absolute;
      left: 11px;
      top: 13px;
    }
    .gurudin-toast button {
      color: white;
    }
    @keyframes progress
    {
      from {width: 100%;}
      to {width: 0%;}
    }
    .gurudin-toast .toast-progress {
      animation: progress 3s;
      position: absolute;
      width: 100%;
      height: 4px;
      background: #616161;
      bottom: 0;
      left: 0;
    }
    .gurudin-toast-danger {
      background-color: #bd362f;
    }
    .gurudin-toast-warning {
      background-color: #f89406;
    }
    .gurudin-toast-info {
      background-color: #2f96b4;
    }
    .gurudin-toast-success {
      background-color: #51a351;
    }
  </style>
  @yield('css')
</head>
<body>

<div class="gurudin-toast" aria-live="assertive" style="display: none;">
  <div class="toast-progress rounded-bottom"></div>
  <button type="button" class="close" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>

  <i></i>
  
  <div class="toast-message align-middle">An unknown error</div>
</div>

<div class="main">
  <div class="left-bar def-bg-color d-none d-xl-block d-lg-block">
    <p class="text-center mt-3">
      <a href="/" class="text-white font20"  data-toggle="tooltip" data-placement="left" title="@lang('frontend.home')"><i class="fas fa-home"></i></a>
    </p>
    <p class="text-center mt-3">
      <a href="/" class="text-white font20"  data-toggle="tooltip" data-placement="left" title="@lang('frontend.list')"><i class="fas fa-list-ol"></i></a>
    </p>
    <p class="text-center mt-3">
      <a href="/" class="text-white font20"  data-toggle="tooltip" data-placement="left" title="@lang('frontend.collect')"><i class="far fa-star"></i></a>
    </p>
  </div>

  <div class="right-content">
    {{-- Nav --}}
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <a class="navbar-brand" href="/">{{ config('app.name', '') }}</a>
      <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="navbar-collapse collapse" id="navbarCollapse">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item active">
            <a class="nav-link" href="/">@lang('frontend.home') <span class="sr-only">(current)</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link disabled" href="#">@lang('frontend.invite')</a>
          </li>
        </ul>

        <form class="form-inline mt-2 mt-md-0">
          
        </form>

        <ul class="navbar-nav  justify-content-end">
          @auth
          <li class="nav-item">
            <div role="group" class="btn-group">
              <button id="in-login-btn" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn in-login-btn dropdown-toggle">
                <img src="{{Auth::user()->avatar}}" width="25" height="25" class="rounded-circle">&nbsp;
                <span>{{Auth::user()->name}}</span>
              </button>
              <div aria-labelledby="in-login-btn" class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item text-dark font13 pointer">
                  <i class="fas fa-user-cog"></i>&nbsp;&nbsp;&nbsp;&nbsp;我的资料
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item text-dark font13 pointer" href="{{route('frontend.logout')}}">
                  <i class="fas fa-sign-out-alt"></i>&nbsp;&nbsp;&nbsp;&nbsp;登出
                </a>
              </div>
            </div>
          </li>
          @endauth

          @unless (Auth::check())
          <li class="nav-item">
            {{-- <a href="{{url(url()->current() . '?blade=login')}}" class="nav-link font12">@lang('frontend.login')</a> --}}
            <a href="javascript:login.show = true;" class="nav-link font12">@lang('frontend.login')</a>
          </li>
          <li class="nav-item">
            {{-- <a href="{{url(url()->current() . '?blade=register')}}" class="nav-link font12">@lang('frontend.register')</a> --}}
            <a href="javascript:register.show = true;" class="nav-link font12">@lang('frontend.register')</a>
          </li>
          @endunless
        </ul>
      </div>
    </nav>

    {{-- carousel & hot --}}
    @section('carousel-hot')
      
    @show
    {{-- carousel & hot --}}

    {{-- hot posts --}}
    @section('recomment-posts')

    @show
    {{-- /hot posts --}}
    
    @yield('content')
  </div>
</div>

<script>
$(document).ready(function(){
  $('[data-toggle="tooltip"]').tooltip();

  /**
   * Toast
   * @param {string} text
   * @param {string} class danger/warning/info/success
   * 
   * @example $(event).loading(); / $(event).loading('reset');
   */
  window.toast = function (text, cls = 'info') {
    var classes = {
      danger: 'fas fa-exclamation-circle',
      warning: 'fas fa-exclamation-triangle',
      info: 'fas fa-info-circle',
      success: 'fas fa-check-circle'
    };
    $(".gurudin-toast .toast-message").html(text);
    $(".gurudin-toast i").attr('class', classes[cls]);
    $(".gurudin-toast").addClass('gurudin-toast-' + cls);

    $(".gurudin-toast").fadeIn();

    setTimeout(() => {
      $(".gurudin-toast").fadeOut();
      $(".gurudin-toast .toast-message").html('');
      $(".gurudin-toast").removeClass('gurudin-toast-' + cls);
    }, 2800);
  };
  $(".gurudin-toast").click(function() {
    $(".gurudin-toast").hide();
    $(".gurudin-toast").attr('class', 'gurudin-toast');
  });
});
</script>
@yield('script')
</body>
</html>