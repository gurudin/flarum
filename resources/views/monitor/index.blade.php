@extends('layouts.monitor.app')

@section('title')
Monitor
@endsection

@section('css')
@endsection

@section('content')
<p class="text-muted"><strong class="font-weight-bold text-dark text-monospace">Feature Tip:</strong> Select block to view monitoring result.</p>

<div class="input-group mb-3">
  <input type="text" class="form-control" v-model="searchKey" placeholder="Search by address / block">
  <div class="input-group-append">
    <button class="btn btn-primary" type="button"><i class="fas fa-search"></i></button>
  </div>
</div>

<div class="list-group">
  <div class="list-group-item">
    <div class="d-flex w-100 justify-content-between">
      <h5 class="mb-1 font-weight-bold">Blockchain list</h5>
      <small>A total of @{{Object.keys(blocks).length}}</small>
    </div>
  </div>

  <a @click="toDetail(item.addr)"
    class="list-group-item list-group-item-action"
    v-for="(item,index) in blocksData">

    @{{item.title}} <small class="text-muted">(@{{item.short.toUpperCase()}})</small><br>
    <small class="text-black-50">Total supply: @{{window.format(item.total)}}</small>

  </a>

  <div class="list-group-item text-center" v-if="blocksData.length == 0">
    <small>No data found.</small>
  </div>

</div>
@endsection

@section('script')
<script>
const vm = new Vue({
  el: '#app-main',
  data() {
    return {
      blocks: @json($blocks),
      searchKey: '',
    };
  },
  computed: {
    blocksData() {
      let key = this.searchKey && this.searchKey.toLowerCase();
      let data = [];

      for (const key in this.blocks) {
        this.blocks[key].title = key;
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
  methods: {
    toDetail(token) {
      let url = "{{route('monitor.detail', ['token' => '@'])}}";
      url = url.replace('@', token);

      window.location = url;
    }
  }
});
</script>
@endsection
