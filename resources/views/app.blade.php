@extends('layouts.blank')

@section('title')
App
@endsection

@section('css')
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
  <style>
  body {
    background: #f2f2f2;
  }
  </style>
@endsection

@section('script')
  <script src="{{ asset('js/main.js') }}" defer></script>
@endsection

@section('content')
<div id="app">
  <router-view></router-view>
</div>
@endsection