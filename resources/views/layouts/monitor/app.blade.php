<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>@yield('title')</title>

  <!-- Styles -->
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
  
  <script src="{{ asset('js/app.js') }}"></script>
  {{-- <script src="{{ asset('js/hm.js') }}"></script> --}}
  
  @yield('css')
</head>
<body style="background: #f2f2f2;">

<nav class="navbar navbar-expand-md navbar-light bg-white">
  <a class="navbar-brand" href="/">Monitor</a>
  {{-- <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="navbar-collapse collapse" id="navbarsExampleDefault" style="">
  </div> --}}
</nav>

<main id="app-main" class="container mt-3 mb-3">
  @yield('content')
</main>

<script>
window.format = function (number, symbol = '') {
  let tmpArr = number.toString().split(".");
  if (tmpArr[0].length <= 3) {
    tmpArr[0] = symbol == '' ? tmpArr[0] : symbol + tmpArr[0];
    return tmpArr[1] ? tmpArr[0] + '.' + tmpArr[1] : tmpArr[0];
  }


  let arr = [];
  let str = tmpArr[0];
  let count = str.length

  while (count >= 3) {
    arr.unshift(str.slice(count - 3, count))
    count -= 3
  }

  str.length % 3 && arr.unshift(str.slice(0, str.length % 3))

  let res = symbol == '' ? arr.toString() : symbol + arr.toString();
  return tmpArr[1] ? res + '.' + tmpArr[1] : res;
}
</script>
@yield('script')
</body>
</html>