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
        <select class="form-control" v-model="typeData">
          <option v-for="(value,key) in init.typeOptions" :value="key">@{{value}}</option>
        </select>
      </div>

      {{-- Recomment options --}}
      <div class="form-group col-8" v-if="init.m.type == 'posts'">
        <label>推荐帖子 <small>*</small></label>
        <div class="input-group mb-3">
          <div class="input-group-prepend">
            <span class="input-group-text">@{{currentPost.title ? currentPost.title : '未选择'}}</span>
          </div>
          <div class="input-group-append">
            <button class="btn btn-outline-info" type="button" data-toggle="modal" data-target="#postsModal">选择帖子</button>
          </div>
        </div>
      </div>

      <div class="form-group col-4" v-if="init.m.type == 'category'">
        <label>推荐类别 <small>*</small></label>
        <select class="form-control" v-model="init.m.recomment_id">
          <option value="">--选择类别--</option>
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
        <label>位置 <small>*</small></label>
        <select class="form-control" v-model="init.m.position">
          <option value="">--选择位置--</option>
          <option v-for="(value,key) in init.positions" :value="key">@{{value}}</option>
        </select>
      </div>

      <div class="form-group col-6">
        <label>封面图片</label>
        <vue-upload-picker
          v-model="init.m.cover"
          :post-uri="href.upload"
          title="点击上传封面图片"
          icon='<i class="fas fa-file-import"></i>'
          class-name="btn btn-primary text-white"></vue-upload-picker>
      </div>

      <div class="form-group col-8">
        <label>描述</label>
        <input type="text" class="form-control" v-model.trim="init.m.remark" placeholder="填写描述">
      </div>

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
        <h5 class="modal-title">选择推荐帖子</h5>
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

          <table class="table table-hover" v-if="posts.length > 0">
            <thead>
              <tr>
                <th scope="col">ID</th>
                <th scope="col">标题</th>
                <th scope="col">操作</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="item in posts">
                <th scope="row">@{{item.id}}</th>
                <td>@{{item.title}}</td>
                <td>
                  <button type="button" class="btn btn-link" @click="slectPost(item)">选择</button>
                </td>
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
      currentPost: {},
      posts: [],
    };
  },
  computed: {
    typeData: {
      get() {
        return this.init.m.type;
      },
      set(value) {
        this.init.m.type = value;
        if (value != 'posts') {
          this.currentPost = {};
          this.init.m.recomment_id = '';
        }
      }
    },
    isValid() {
      var m = this.init.m;
      if (m.position == '') {
        return false;
      }

      return true;
    }
  },
  methods: {
    searchPosts(event) {
      var key = this.$refs['posts-key'].value;
      if (key == '') {
        return false;
      }

      var $btn = $(event.currentTarget).loading('<i class="fas fa-spinner fa-spin"></i>');
      var _this = this;
      axios.post(this.href.current, {
        action: 'posts',
        key: key
      }).then(function (response) {
        return response.data;
      }).then(function (rep) {
        if (rep.status) {
          _this.posts = rep.data;
        } else {
          alert(rep.msg);
        }
        $btn.loading('reset');
      });
    },
    slectPost(item) {
      this.currentPost = item;
      this.init.m.recomment_id = item.id;
      $("#postsModal").modal('hide');
    },
    save(event) {
      if (this.init.m.recomment_id == '') {
        if (this.init.m.type == 'posts') {
          alert('请选择推荐帖子');
          return false;
        }
        if (this.init.m.type == 'category') {
          alert('请选择推荐分类');
          return false;
        }
      }
      if (this.init.m.type == 'url' && this.init.m.url == '') {
        alert('请填写跳转链接');
        return false;
      }
      var $btn = $(event.currentTarget).loading('<i class="fas fa-spinner fa-spin"></i>');
      var _this = this;
      axios.post(this.href.current, {
        action: 'save',
        data: this.init.m
      }).then(function (response) {
        if (response.status) {
          window.location.href = _this.href.index;
        } else {
          alert(response.msg);
          $btn.loading('reset');
        }
      });
    },
  }
});
</script>
@endsection
