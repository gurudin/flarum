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
  
  @yield('css')
  <style>
    .left-bar {
      position: fixed;
      left: 0px;
      top: 0px;
      width: 55px;
      padding: 0;
      height: 100%;
      box-shadow: -2px 0px 4px rgba(0,0,0,1);
      z-index: 98;
      background: #12c5ca;
    }
    .right-content {
      z-index: 1;
      margin-bottom: 0px;
      float: none;
      position: relative;
    }
  </style>
</head>
<body>
  <div class="main">
    <div class="left-bar d-none d-xl-block d-lg-block">
      <p class="text-center mt-3">
        <a href="" class="text-white font20"  data-toggle="tooltip" data-placement="left" title="首页"><i class="fas fa-home"></i></a>
      </p>
      <p class="text-center mt-3">
        <a href="" class="text-white font20"  data-toggle="tooltip" data-placement="left" title="排行榜"><i class="fas fa-list-ol"></i></a>
      </p>
      <p class="text-center mt-3">
        <a href="" class="text-white font20"  data-toggle="tooltip" data-placement="left" title="收藏"><i class="far fa-star"></i></a>
      </p>
    </div>

    <div class="right-content">
      {{-- Nav --}}
      <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Carousel</a>
        <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="navbar-collapse collapse" id="navbarCollapse" style="">
          <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
              <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">Link</a>
            </li>
            <li class="nav-item">
              <a class="nav-link disabled" href="#">Disabled</a>
            </li>
          </ul>

          <form class="form-inline mt-2 mt-md-0">
            
          </form>

          <ul class="navbar-nav  justify-content-end">
            <li class="nav-item">
              <a href="#" class="nav-link font12">登入</a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link font12">注册</a>
            </li>
          </ul>
        </div>
      </nav>

      {{-- carousel & hot --}}
      @section('carousel-hot')
        
      @show
      {{-- carousel & hot --}}
    </div>
  </div>



  {{-- hot posts --}}
  {{-- @section('recomment-posts')

  @show --}}
  {{-- /hot posts --}}

  
  @yield('content')

  <script src="{{ asset('js/app.js') }}"></script>
  <script>
    $(document).ready(function(){
      $('[data-toggle="tooltip"]').tooltip();
    });
    (function ($) {
      /**
       * Button for loading
       * @param {string} text
       * 
       * @example $(event).loading(); / $(event).loading('reset');
       */
      var loadingDefaultValue = '';
      $.fn.loading = function (text = '') {
        text == '' ? text = 'loading...' : text;
        if (text == 'reset') {
          this.removeClass('disabled').attr('disabled', false).html(loadingDefaultValue);
        } else {
          loadingDefaultValue = this[0].innerHTML;
          this.addClass('disabled').attr('disabled', true).html(text);
          
          return this;
        }
      };
    })(jQuery);
  </script>
  @yield('script')
</body>
</html>