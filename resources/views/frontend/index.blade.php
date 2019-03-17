@extends('layouts.frontend.app')

@section('title')

@endsection

@section('css')
@endsection

@section('script')
  
@endsection

@section('carousel-hot')
<div class="container-fluid mt-3 mb-3 d-none d-xl-block d-lg-block">
  <div class="carousel-hot">
    <div class="carousel-img">
      <div id="carouselHot" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">
          @foreach ($recomments as $inx => $recomment)
            @continue($recomment['position'] != 1)
            <li data-target="#carouselHot" data-slide-to="{{$inx}}" class="{{$inx == 0 ? 'active' : ''}}"></li>
          @endforeach
        </ol>
        <div class="carousel-inner">

          @foreach ($recomments as $inx => $recomment)
            @continue($recomment['position'] != 1)
            <div class="carousel-item {{$inx == 0 ? 'active' : ''}}">
              @if ($recomment['type'] == 'posts')
                <a href="{{route('frontend.post', ['id' => $recomment['recomment_id']])}}" target="_blank">
              @elseif ($recomment['type'] == 'category')
                <a href="{{route('frontend.posts', ['alias' => $recomment['alias']])}}">
              @else
                <a href="{{$recomment['url']}}" target="_blank">
              @endif
                <img class="rounded" width="600" height="300" src="{{$recomment['cover']}}" alt="...">
              </a>
              <div class="carousel-caption d-none d-md-block">
                <p>
                  {{$recomment['remark']=='' ? (isset($recomment['title']) ? $recomment['title'] : '') : $recomment['remark']}}
                </p>
              </div>
            </div>
          @endforeach

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

    <div class="tabs-box bg-white rounded">
      <div class="tabs">
        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist" style="padding-left: 1.25rem;">
          <li class="nav-item">
            <a class="nav-link active text-muted" data-toggle="pill" href="#pills-home" role="tab" aria-selected="true">@lang('frontend.latest-posts')</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-muted" data-toggle="pill" href="#pills-profile" role="tab" aria-selected="false">@lang('frontend.top-posts')</a>
          </li>
        </ul>
        <div class="tab-content" id="pills-tabContent">
          {{-- Latest --}}
          <div class="tab-pane fade show active" id="pills-home" role="tabpanel">
            <ol class="list-group list-group-flush">
              @foreach ($posts['latest'] as $inx => $item)
              <li class="list-group-item">
                <span class="text-muted font-italic">{{$inx + 1}}. </span>
                <a href="{{route('frontend.post', ['id' => $item->id])}}" target="_blank" class="text-dark">{{$item->title}}</a>
                <div class="float-right text-muted font12">{{date('m/d', strtotime($item->created_at))}}</div>
              </li>
              @endforeach
            </ol>
          </div>

          {{-- Hots --}}
          <div class="tab-pane fade" id="pills-profile" role="tabpanel">
            <ol class="list-group list-group-flush">
              @foreach ($posts['hots'] as $inx => $item)
              <li class="list-group-item">
                <span class="text-muted font-italic">{{$inx + 1}}. </span>
                <a href="{{route('frontend.post', ['id' => $item->id])}}" target="_blank" class="text-dark">{{$item->title}}</a>
                <div class="float-right text-muted font12">{{date('m/d', strtotime($item->created_at))}}</div>
              </li>
              @endforeach
            </ol>
          </div>
        </div>
      </div>
    </div>
    
  </div>
</div>
@endsection

@inject('metrics', 'App\Utils\Common')
@section('recomment-posts')

<div class="container-fluid recomment-posts mt-3 mb-3 d-none d-xl-block d-lg-block">
  @foreach ($recomments as $inx => $recomment)
    @continue($recomment['position'] != 2)

    @if ($recomment['type'] == 'posts')
      <a href="{{route('frontend.post', ['id' => $recomment['recomment_id']])}}" target="_blank" class="rounded" style="background-color: {{$metrics->randColor()}};">
    @elseif ($recomment['type'] == 'category')
      <a href="{{route('frontend.posts', ['alias' => $recomment['alias']])}}" class="rounded" style="background-color: {{$metrics->randColor()}};">
    @else
      <a href="{{$recomment['url']}}" target="_blank" class="rounded" style="background-color: {{$metrics->randColor()}};">
    @endif
      <h5 class="nowrap font15">{{$recomment['title']}}</h5>
      <p class="nowrap text-white-50 font12">{{$recomment['remark']}}</p>
    </a>
  @endforeach
</div>
<div class="clearfix"></div>
@endsection

@section('content')
<div class="container-fluid mb-3">

  @foreach ($categorys as $category)
    <div class="panel-category bg-white rounded mt-3">
      <nav class="navbar bg-secondary rounded-top">
        <a class="text-white" href="{{url(url()->current() . '?alias=' . $category['alias'])}}">{{$category['category']}}</a>

        <a class="text-white" data-toggle="collapse" href="#collapse{{$category['id']}}" aria-expanded="false">
          <i class="fas fa-angle-down"></i>
        </a>
      </nav>

      <div class="collapse show row col-12" id="collapse{{$category['id']}}">
        
        @foreach ($category['children'] as $child)
        <div class="media mt-2 mb-1 col-xl-3 col-12">
          <a href="{{route('frontend.posts', ['alias' => $child['alias']])}}">
            <img class="mr-2 pointer rounded" width="64" height="64" src="{{$child['pic']}}" alt="{{$child['category']}}" title="{{$child['category']}}">
          </a>
          <div class="media-body nowrap">
            <a href="{{route('frontend.posts', ['alias' => $child['alias']])}}" class="mt-0 font15 def-color pointer">
              {{$child['category']}}
            </a>
            <p class="font12 text-muted">@lang('frontend.posts.total_posts'): {{$child['total']}}</p>
            <p class="font12 text-muted">@lang('frontend.posts.latest_at'): {{date('Y.m.d', strtotime($child['latest_at']))}}</p>
          </div>
        </div>
        @endforeach
        
      </div>
    </div>
  @endforeach

</div>

@login(['blade' => $blade])
@endlogin

@register(['blade' => $blade])
@endregister
@endsection