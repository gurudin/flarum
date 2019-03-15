@extends('layouts.admin')

@section('title')
Recomment
@endsection

@section('css')

@endsection

@section('content')
<div class="card">
  <div class="card-body">
    <h4 class="card-title">推荐位管理 
      <a :href="init.href.save" class="btn btn-outline-success float-right">
        <i class="fas fa-file-signature"></i> 创建
      </a>
    </h4>
    <hr>

    <table class="table table-hover">
      <thead>
        <tr>
          <th scope="col">Weight</th>
          <th scope="col">Category</th>
          <th scope="col">Alias</th>
          <th scope="col">Icon</th>
          <th scope="col">Color</th>
          <th>Enabled</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        
      </tbody>
    </table>
    
    <div>
      <div class="float-right">
      </div>
    </div>
  </div>
</div>
@endsection

@section('script')
<script src="{{ asset('js/vue-toggle-button.min.js') }}"></script>
<script>
const vm = new Vue({
  el: "#app",
  data() {
    return {
      init: {
        href: {
          save: "{{route('admin.recomment.save')}}",
          current: "{{route('admin.recomment.list')}}"
        },
      },
      options: [
        {label: "Active", value: 1, "checked": "success"},
        {label: "Deactive", value: 0, checked: "warning"}
      ],
    };
  },
  methods: {
    
  }
});
</script>
@endsection
