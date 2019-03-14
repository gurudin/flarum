@extends('layouts.frontend.app')

@section('title')

@endsection

@section('css')
@endsection

@section('script')
  
@endsection

@section('carousel-hot')
{{-- <div class="container-fluid mt-3 mb-3 d-none d-xl-block d-lg-block">
  <div class="carousel-hot">
    <div class="carousel-img">
      <div id="carouselHot" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">
          <li data-target="#carouselHot" data-slide-to="0" class="active"></li>
          <li data-target="#carouselHot" data-slide-to="1"></li>
        </ol>
        <div class="carousel-inner">
          <div class="carousel-item active">
            <img class="d-block w-100 rounded" src="https://d9iyrkd8y2zpr.cloudfront.net/webapi-assets-dev/resources/banners/201903/cms3-message-sqbnu9uq7rsj2ruc.png" alt="...">
            <div class="carousel-caption d-none d-md-block">
              <p>slide 1</p>
            </div>
          </div>

          <div class="carousel-item">
            <img class="d-block w-100 rounded" src="https://d9iyrkd8y2zpr.cloudfront.net/webapi-assets-dev/resources/banners/201903/cms3-message-sqbnu9uq7rsj2ruc.png" alt="...">
            <div class="carousel-caption d-none d-md-block">
              <p>slide 2</p>
            </div>
          </div>
        </div>
        <a class="carousel-control-prev" href="#carouselHot" role="button" data-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselHot" role="button" data-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="sr-only">Next</span>
        </a>
      </div>
    </div>

    <div class="tabs-box w-100 bg-white rounded">
      <div class="tabs">
        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
          <li class="nav-item">
            <a class="nav-link active text-muted" data-toggle="pill" href="#pills-home" role="tab" aria-selected="true">@lang('frontend.latest-posts')</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-muted" data-toggle="pill" href="#pills-profile" role="tab" aria-selected="false">@lang('frontend.top-posts')</a>
          </li>
        </ul>
        <div class="tab-content" id="pills-tabContent">
          <div class="tab-pane fade show active" id="pills-home" role="tabpanel">
            <ul class="list-group list-group-flush">
              <li class="list-group-item">Cras justo odio</li>
              <li class="list-group-item">Dapibus ac facilisis in</li>
              <li class="list-group-item">Morbi leo risus</li>
              <li class="list-group-item">Porta ac consectetur ac</li>
              <li class="list-group-item">Vestibulum at eros</li>
            </ul>
          </div>
          <div class="tab-pane fade" id="pills-profile" role="tabpanel">2</div>
          <div class="tab-pane fade" id="pills-contact" role="tabpanel">3</div>
        </div>
      </div>
    </div>
    
  </div>
</div> --}}
@endsection

@section('recomment-posts')
{{-- <div class="container-fluid recomment-posts mt-3 mb-3 d-none d-xl-block d-lg-block" >
  <a href="#" class="rounded">
    <h5 class="nowrap font15">ROSI写真 2019-03-10 NO.2650 [33+1P/28.4M</h5>
    <p class="nowrap text-white-50 font12">ROSI写ROSI写真 2019-03-10 NO.2650 [33+1P/28.4M真</p>
  </a>
  <a href="#" class="rounded">1</a>
  <a href="#" class="rounded">1</a>
  <a href="#" class="rounded">1</a>
  <a href="#" class="rounded">1</a>
  <a href="#" class="rounded">1</a>
  <a href="#" class="rounded">1</a>
  <a href="#" class="rounded">1</a>
  <a href="#" class="rounded">1</a>
  <a href="#" class="rounded">1</a>
</div> --}}
@endsection

@section('content')
<div class="container-fluid mb-3">

  <div class="panel-category bg-white rounded mt-3">
    <nav class="navbar bg-dark rounded-top">
      <a class="text-white" href="#">Navbar w/ text</a>

      <a class="text-white" data-toggle="collapse" href="#collapse1" aria-expanded="false">
        <i class="fas fa-angle-down"></i>
      </a>
    </nav>

    <div class="collapse show row col-12" id="collapse1">
      
      <div class="media mt-2 mb-1 col-xl-3 col-12">
        <img class="mr-2 pointer" width="64" height="64" src="https://d9iyrkd8y2zpr.cloudfront.net/webapi-assets-dev/resources/banners/201903/cms3-message-sqbnu9uq7rsj2ruc.png" alt="Generic placeholder image">
        <div class="media-body nowrap">
          <b class="mt-0 h5 def-color pointer">Media heading</b>
          <p class="font12 text-muted">主题: 123</p>
          <p class="font12 text-muted">最后发表时间: 13.12.11</p>
        </div>
      </div>

      <div class="media mt-2 mb-1 col-xl-3 col-12">
        <img class="mr-2 pointer" width="64" height="64" src="https://d9iyrkd8y2zpr.cloudfront.net/webapi-assets-dev/resources/banners/201903/cms3-message-sqbnu9uq7rsj2ruc.png" alt="Generic placeholder image">
        <div class="media-body nowrap">
          <b class="mt-0 h5 def-color pointer">Media heading</b>
          <p class="font12 text-muted">主题: 123</p>
          <p class="font12 text-muted">最后发表时间: 13.12.11</p>
        </div>
      </div>

      <div class="media mt-2 mb-1 col-xl-3 col-12">
        <img class="mr-2 pointer" width="64" height="64" src="https://d9iyrkd8y2zpr.cloudfront.net/webapi-assets-dev/resources/banners/201903/cms3-message-sqbnu9uq7rsj2ruc.png" alt="Generic placeholder image">
        <div class="media-body nowrap">
          <b class="mt-0 h5 def-color pointer">Media heading</b>
          <p class="font12 text-muted">主题: 123</p>
          <p class="font12 text-muted">最后发表时间: 13.12.11</p>
        </div>
      </div>

      <div class="media mt-2 mb-1 col-xl-3 col-12">
        <img class="mr-2 pointer" width="64" height="64" src="https://d9iyrkd8y2zpr.cloudfront.net/webapi-assets-dev/resources/banners/201903/cms3-message-sqbnu9uq7rsj2ruc.png" alt="Generic placeholder image">
        <div class="media-body nowrap">
          <b class="mt-0 h5 def-color pointer">Media heading</b>
          <p class="font12 text-muted">主题: 123</p>
          <p class="font12 text-muted">最后发表时间: 13.12.11</p>
        </div>
      </div>

      <div class="media mt-2 mb-1 col-xl-3 col-12">
        <img class="mr-2 pointer" width="64" height="64" src="https://d9iyrkd8y2zpr.cloudfront.net/webapi-assets-dev/resources/banners/201903/cms3-message-sqbnu9uq7rsj2ruc.png" alt="Generic placeholder image">
        <div class="media-body nowrap">
          <b class="mt-0 h5 def-color pointer">Media heading</b>
          <p class="font12 text-muted">主题: 123</p>
          <p class="font12 text-muted">最后发表时间: 13.12.11</p>
        </div>
      </div>

      <div class="media mt-2 mb-1 col-xl-3 col-12">
        <img class="mr-2 pointer" width="64" height="64" src="https://d9iyrkd8y2zpr.cloudfront.net/webapi-assets-dev/resources/banners/201903/cms3-message-sqbnu9uq7rsj2ruc.png" alt="Generic placeholder image">
        <div class="media-body nowrap">
          <b class="mt-0 h5 def-color pointer">Media heading</b>
          <p class="font12 text-muted">主题: 123</p>
          <p class="font12 text-muted">最后发表时间: 13.12.11</p>
        </div>
      </div>
      
    </div>
  </div>

  <div class="panel-category bg-white rounded mt-3">
    <nav class="navbar bg-dark rounded-top">
      <a class="text-white" href="#">Navbar w/ text</a>

      <a class="text-white" data-toggle="collapse" href="#collapse2" aria-expanded="false">
        <i class="fas fa-angle-down"></i>
      </a>
    </nav>

    <div class="collapse show row col-12" id="collapse2">
      
      <div class="media mt-2 mb-1 col-xl-3 col-12">
        <img class="mr-2 pointer" width="64" height="64" src="https://d9iyrkd8y2zpr.cloudfront.net/webapi-assets-dev/resources/banners/201903/cms3-message-sqbnu9uq7rsj2ruc.png" alt="Generic placeholder image">
        <div class="media-body nowrap">
          <b class="mt-0 h5 def-color pointer">Media heading</b>
          <p class="font12 text-muted">主题: 123</p>
          <p class="font12 text-muted">最后发表时间: 13.12.11</p>
        </div>
      </div>

      <div class="media mt-2 mb-1 col-xl-3 col-12">
        <img class="mr-2 pointer" width="64" height="64" src="https://d9iyrkd8y2zpr.cloudfront.net/webapi-assets-dev/resources/banners/201903/cms3-message-sqbnu9uq7rsj2ruc.png" alt="Generic placeholder image">
        <div class="media-body nowrap">
          <b class="mt-0 h5 def-color pointer">Media heading</b>
          <p class="font12 text-muted">主题: 123</p>
          <p class="font12 text-muted">最后发表时间: 13.12.11</p>
        </div>
      </div>

      <div class="media mt-2 mb-1 col-xl-3 col-12">
        <img class="mr-2 pointer" width="64" height="64" src="https://d9iyrkd8y2zpr.cloudfront.net/webapi-assets-dev/resources/banners/201903/cms3-message-sqbnu9uq7rsj2ruc.png" alt="Generic placeholder image">
        <div class="media-body nowrap">
          <b class="mt-0 h5 def-color pointer">Media heading</b>
          <p class="font12 text-muted">主题: 123</p>
          <p class="font12 text-muted">最后发表时间: 13.12.11</p>
        </div>
      </div>

      <div class="media mt-2 mb-1 col-xl-3 col-12">
        <img class="mr-2 pointer" width="64" height="64" src="https://d9iyrkd8y2zpr.cloudfront.net/webapi-assets-dev/resources/banners/201903/cms3-message-sqbnu9uq7rsj2ruc.png" alt="Generic placeholder image">
        <div class="media-body nowrap">
          <b class="mt-0 h5 def-color pointer">Media heading</b>
          <p class="font12 text-muted">主题: 123</p>
          <p class="font12 text-muted">最后发表时间: 13.12.11</p>
        </div>
      </div>

      <div class="media mt-2 mb-1 col-xl-3 col-12">
        <img class="mr-2 pointer" width="64" height="64" src="https://d9iyrkd8y2zpr.cloudfront.net/webapi-assets-dev/resources/banners/201903/cms3-message-sqbnu9uq7rsj2ruc.png" alt="Generic placeholder image">
        <div class="media-body nowrap">
          <b class="mt-0 h5 def-color pointer">Media heading</b>
          <p class="font12 text-muted">主题: 123</p>
          <p class="font12 text-muted">最后发表时间: 13.12.11</p>
        </div>
      </div>

      <div class="media mt-2 mb-1 col-xl-3 col-12">
        <img class="mr-2 pointer" width="64" height="64" src="https://d9iyrkd8y2zpr.cloudfront.net/webapi-assets-dev/resources/banners/201903/cms3-message-sqbnu9uq7rsj2ruc.png" alt="Generic placeholder image">
        <div class="media-body nowrap">
          <b class="mt-0 h5 def-color pointer">Media heading</b>
          <p class="font12 text-muted">主题: 123</p>
          <p class="font12 text-muted">最后发表时间: 13.12.11</p>
        </div>
      </div>
      
    </div>
  </div>

</div>

@login(['blade' => $blade])
@endlogin

@register(['blade' => $blade])
@endregister
@endsection