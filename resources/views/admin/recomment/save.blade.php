@extends('layouts.admin')

@section('title')
Recomment
@endsection

@section('css')
  
@endsection

@section('content')
<div class="card">
  <div class="card-body">
    <h4 class="card-title">
      推荐位管理 <small class="text-muted">create & update</small>
      <a :href="href.index" class="btn btn-light float-right">
        <i class="fas fa-arrow-left"></i> Back
      </a>
    </h4>
    <hr>

    <form>
      <div class="form-group col-4">
        <label>推荐类型 <small>*</small></label>
        <select class="form-control" v-model="init.m.type">
          <option v-for="(value,key) in init.typeOptions" :value="key">@{{value}}</option>
        </select>
      </div>

      {{-- Recomment options --}}
      <div class="form-group col-8" v-if="init.m.type == 'posts'">
        <label>推荐帖子 <small>*</small></label>
        <div class="input-group mb-3">
          <div class="input-group-prepend">
            <span class="input-group-text">未选择</span>
          </div>
          <div class="input-group-append">
            <button class="btn btn-outline-info" type="button" data-toggle="modal" data-target="#postsModal">选择帖子</button>
          </div>
        </div>
      </div>

      <div class="form-group col-4" v-if="init.m.type == 'category'">
        <label>推荐类别 <small>*</small></label>
        <select class="form-control" v-model="init.m.recomment_id">
          <option value="">选择类别</option>
          <optgroup v-for="item in init.categorys" :label="item.category">
              <option v-for="child in item.children" :value="child.id">@{{child.category}}</option>
            </optgroup>
        </select>
      </div>

      <div class="form-group col-8" v-if="init.m.type == 'url'">
        <label>跳转链接 <small>*</small></label>
        <input type="text" class="form-control" v-model.trim="init.m.url" placeholder="链接地址">
      </div>
      {{-- /Recomment options --}}
      

      <div class="form-group col-4">
        <button type="button" class="btn btn-success" :disabled="!isValid" @click="save">
          <i class="fas fa-paper-plane"></i> Save
        </button>
      </div>
    </form>
  </div>
</div>

{{-- Select posts modal --}}
<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" id='postsModal'>
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">选择推荐帖子</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="input-group mb-3">
          <input type="text" class="form-control" placeholder="输入帖子id 或 帖子标题关键字" ref="posts-key">
          <div class="input-group-append">
            <button class="btn btn-outline-success" type="button" @click="searchPosts"><i class="fas fa-search"></i> 搜索</button>
          </div>

          <table class="table table-hover">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">First</th>
                <th scope="col">Last</th>
                <th scope="col">Handle</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <th scope="row">1</th>
                <td>Mark</td>
                <td>Otto</td>
                <td>@mdo</td>
              </tr>
              <tr>
                <th scope="row">2</th>
                <td>Jacob</td>
                <td>Thornton</td>
                <td>@fat</td>
              </tr>
              <tr>
                <th scope="row">3</th>
                <td colspan="2">Larry the Bird</td>
                <td>@twitter</td>
              </tr>
            </tbody>
          </table>

        </div>
      </div>
    </div>
  </div>
</div>
{{-- /Select posts modal --}}
@endsection

@section('script')
<script src="{{ asset('js/vue-upload-picker.js') }}"></script>
<script>
new Vue({
  el: "#app",
  data() {
    return {
      init: {
        m: @json($m),
        categorys: @json($categorys),
        typeOptions: @json($type_options),
        positions: @json($positions),
      },
      href: {
        index: "{{route('admin.recomment.list')}}",
        current: "{{route('admin.recomment.save')}}",
        upload: "{{route('admin.upload')}}",
      },
    };
  },
  computed: {
    isValid() {

      return true;
    }
  },
  methods: {
    openModal() {
      $("#postsModal").modal('show');
    },
    searchPosts() {

    },
    save(event) {
      // var $btn = $(event.currentTarget).loading('<i class="fas fa-spinner fa-spin"></i>');
      // var _this = this;
      // axios.post(this.href.current, {
      //   action: 'save',
      //   data: this.init.m
      // }).then(function (response) {
      //   if (response.status) {
      //     window.location.href = _this.href.index;
      //   } else {
      //     alert(response.msg);
      //     $btn.loading('reset');
      //   }
      // });
    },
  }
});
</script>
@endsection
