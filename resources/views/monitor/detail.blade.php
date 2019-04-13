@extends('layouts.monitor.app')

@section('title')
{{$currentBlock['title']}}
@endsection

@section('css')
@endsection

@section('content')
<p class="text-muted"><strong class="font-weight-bold text-dark text-monospace">Feature Tip:</strong> Select block to view monitoring result.</p>

<div class="bg-white">
  <v-select label="title" :options="blocksData" v-model="currentBlock" placeholder="Search by address / block"></v-select>
</div>

<div class="card mt-3 mb-3">
  <div class="card-body">
    <h5 class="card-title">
      基本统计
      <div role="group" class="btn-group btn-group-sm float-right">
        <button type="button" class="btn btn-secondary" :class="{'active': base.currentDate==7}" @click="changeDate(7)">7天</button>
        <button type="button" class="btn btn-secondary" :class="{'active': base.currentDate==15}" @click="changeDate(15)">15天</button>
        <button type="button" class="btn btn-secondary" :class="{'active': base.currentDate==30}" @click="changeDate(30)">1月</button>
      </div>
    </h5>
    {{-- This is some text within a card body. --}}
    <p class="text-center" v-if="base.chartData.rows.length == 0"><i class="fas fa-spinner fa-spin"></i></p>
    {{-- base --}}
    <ve-line :data="base.chartData" :y-axis="yAxis" :legend="baseLegend" :tooltip="tooltip" height="300px" :settings="baseSettings" v-if="base.chartData.rows.length > 0"></ve-line>
    
    {{-- <ve-line :data="base.chartData" height="300px" :settings="baseSettings" v-if="base.chartData.rows.length > 0"></ve-line> --}}
  </div>
</div>

@endsection

@section('script')
<script src="{{ asset('js/vue-select.js') }}"></script>
<script src="{{ asset('js/echarts/echarts.min.js') }}"></script>
<script src="{{ asset('js/echarts/line.min.js') }}"></script>
<script>
Vue.component('v-select', VueSelect.VueSelect);
const vm = new Vue({
  el: '#app-main',
  data() {
    this.baseSettings = {
      labelMap: {
        'price_usd': '价格 USD',
        'price_eth': '价格 ETH',
        'applies': '涨跌幅度 百分比',
      },
    };
    this.yAxis = {
      min: function(value) {
        return value.min;
      },
      max: function(value) {
        return value.max;
      }
    }
    this.baseLegend = {
      selected: {
        '价格 ETH': false,
        '涨跌幅度 百分比': false,
      },
    }
    this.tooltip = {
      formatter: function(value) {
        let result = '<i class="fas fa-calendar-week"></i> ' + value.value[0] + '<br>';
        switch (value.seriesName) {
          case '价格 USD':
            result += 'USD <span style="color:gold;">$' + value.value[1] + '</span>';
            break;
          case '价格 ETH':
            result += 'ETH ' + value.value[1];
            break;
          case '涨跌幅度 百分比':
            result += '百分比: <span style="color:' + (value.value[1] > 0 ? '#62D0B0' : '#E25D52') + '">' + value.value[1] + '%</span>';
            break;
          default:
            result += value.value[1];
            break;
        }
        return result;
      }
    }
    return {
      currentBlock: @json($currentBlock),
      blocks: @json($blocks),
      searchKey: '',
      base: {
        currentDate: 7,
        chartData: {
          columns: ['date', 'price_usd', 'price_eth', 'applies'],
          rows: []
        }
      },
    };
  },
  computed: {
    blocksData() {
      let key = this.searchKey && this.searchKey.toLowerCase();
      let data = [];

      for (const key in this.blocks) {
        this.blocks[key].title = key + ' (' + this.blocks[key].short.toUpperCase() + ')';
        data.push(this.blocks[key]);
      }

      if (key != '') {
        data = data.filter(row =>{
          return row.short.toLowerCase().indexOf(key) > -1 || row.title.toLowerCase().indexOf(key) > -1;
        });
      }
      
      return data;
    },
  },
  components: { VeLine },
  watch: {
    currentBlock() {
      let url = "{{route('monitor.detail', ['token' => '@'])}}";
      url = url.replace('@', this.currentBlock.addr);
      
      window.location.href = url;
    }
  },
  methods: {
    changeDate(day) {
      this.base.chartData.rows=[];
      this.base.currentDate = day;
      this.getBaseData(this.base.currentDate);
    },
    getBaseData(day) {
      var _this = this;
      axios.post("{{route('monitor.getBaseData')}}", {
        form: moment().subtract(day, 'd').format('YYYY-MM-DD'),
        to: moment().format('YYYY-MM-DD'),
        block: this.currentBlock.name
      }).then(function (resp) {
        return resp.data;
      }).then(function (resp) {
        if (resp.status) {
          _this.base.chartData.rows = resp.data;
        }
      });
    },
  },
  created() {
    this.getBaseData(this.base.currentDate);
  }
});
</script>
@endsection
