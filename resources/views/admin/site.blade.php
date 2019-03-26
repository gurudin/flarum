@extends('layouts.admin')

@section('title')
Welcome {{ Auth::user()->name }}
@endsection

@section('css')
@endsection

@section('content')
<div class="jumbotron bg-white">
  <h1 class="display-4">Welcome, {{ Auth::user()->name }}!</h1>
  <p class="lead">You have successfully logged in Widget Admin.</p>
  <hr class="my-4">
  <p><i class="fas fa-exclamation-triangle"></i> We log every actions and operations, please use with care.</p>
</div>

<div class="card">
  <div class="card-body">
    <h5 class="card-title">
      今日访问统计
      <div class="btn-group btn-group-sm float-right" role="group">
        <button type="button" class="btn btn-secondary" :class="{'active': currentDate=='yesterday'}" @click="changeDate('yesterday')">昨</button>
        <button type="button" class="btn btn-secondary" :class="{'active': currentDate=='today'}" @click="changeDate('today')">今</button>
      </div>
    </h5>
    This is some text within a card body.
    <p class="text-center" v-if="chartData.rows.length == 0"><i class="fas fa-spinner fa-spin"></i></p>
    <ve-line :data="chartData" :settings="chartSettings"></ve-line>
  </div>
</div>
@endsection

@section('script')
  <script src="{{ asset('js/echarts/echarts.min.js') }}"></script>
  <script src="{{ asset('js/echarts/line.min.js') }}"></script>
  <script>
    new Vue({
      el: "#app",
      data() {
        this.chartSettings = {
          labelMap: {
            'cnt': '访问量',
          }
        }
        return {
          currentDate: 'today', // today, yesterday
          chartData: {
            columns: ['date', 'cnt'],
            rows: [
              // { 'date': '1/1', 'pv': 1393 },
              // { 'date': '1/2', 'pv': 3530 },
              // { 'date': '1/3', 'pv': 2923 },
              // { 'date': '1/4', 'pv': 1723 },
              // { 'date': '1/5', 'pv': 3792 },
              // { 'date': '1/6', 'pv': 4593 }
            ]
          }
        };
      },
      components: { VeLine },
      methods: {
        getData(date) {
          var _this = this;
          axios.post("{{route('admin.home')}}", {
            action: 'charts',
            date: date
          }).then(function (resp) {
            return resp.data;
          }).then(function (resp) {
            if (resp.status) {
              for (const key in resp.data) {
                console.log(resp.data[key]);
                _this.chartData.rows.push(resp.data[key]);
              }
              
              // _this.chartData.rows = resp.data;
            }
          });
        },
        changeDate(date) {
          this.chartData.rows = [];
          this.currentDate = date;
          this.getData(date);
        }
      },
      created() {
        this.getData(this.currentDate);
      }
    });
  </script>
@endsection
