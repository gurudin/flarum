@extends('layouts.admin')

@section('title') Posts @endsection

@section('css')

@endsection

@section('content')
<div class="card">
  <div class="card-body">
    <h4 class="card-title">Posts
      <a :href="init.href.save" class="btn btn-outline-success float-right">
        <i class="fas fa-file-signature"></i> Create
      </a>
    </h4>
    <hr>

    <form>
      <div class="form-row">
        <div class="col-md-3 mb-3">
          <label>Category</label>
          <select class="form-control" v-model="keys.fk_category_id">
            <option value="">-- Select category --</option>
            <optgroup v-for="item in init.categorys" :label="item.category">
              <option v-for="child in item.children" :value="child.id">@{{child.category}}</option>
            </optgroup>
          </select>
        </div>

        <div class="col-md-2 mb-3">
          <label>Status</label>
          <select class="form-control" v-model="keys.status">
            <option value="">-- All status --</option>
            <option value="0">Offline</option>
            <option value="1">Online</option>
          </select>
        </div>

        <div class="col-md-1 ml-auto">
          <label></label><br>
          <button type="button" class="btn float-right btn-primary" @click="search">
            <i class="fas fa-search"></i>
          </button>
        </div>
      </div>
    </form>

    <table class="table table-hover">
      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">Title</th>
          <th scope="col">Category</th>
          <th scope="col">Coin</th>
          <th scope="col">Pv</th>
          <th scope="col">Level</th>
          <th>Status</th>
          <th>Created at</th>
          <th>Action</th>
        </tr>
      </thead>

      <tbody>
        <tr v-for="(item,inx) in init.list">
          <th scope="row">@{{item.id}}</th>
          <td>@{{item.title}}</td>
          <td>@{{getName(item.fk_category_id)}}</td>
          <td>@{{item.coin}}</td>
          <td>@{{item.real_pv}}</td>
          <td>@{{getLevel(item.read_level)}}</td>
          <td>
            <toggle-button
              :data="item"
              v-model="item.status"
              size="sm"
              :options="options"
              :before="editStatus"></toggle-button>
          </td>
          <td>@{{moment(item.created_at).format('YYYY/MM/DD HH:mm')}}</td>
          <td>
            <button
              type="button"
              class="btn btn-warning btn-sm"
              @click="toEdit(item.id)">
              <i class="fas fa-edit"></i></button>
            
            <button
              type="button"
              class="btn btn-danger btn-sm"
              @click="remove($event, item.id, inx)">
              <i class="fas fa-trash-alt"></i></button>
          </td>
        </tr>
      </tbody>
    </table>

    <div>
      <div class="float-right">
        {{ $posts->links() }}
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
        list: @json($posts).data,
        categorys: @json($categorys),
        levels: @json($levels),
        href: {
          current: "{{route('admin.posts.list')}}",
          save: "{{route('admin.posts.save')}}",
        },
      },
      options: [
        {label: "Online", value: 1, "checked": "success"},
        {label: "Offline", value: 0, checked: "warning"}
      ],
      keys: @json($search),
    };
  },
  methods: {
    getLevel(level) {
      return this.init.levels[level];
    },
    getName(id) {
      var categoryName = '';
      this.init.categorys.forEach(row =>{
        row.children.forEach(child =>{
          if (child.id == id) {
            categoryName = child.category;
          }
        });
      });

      return categoryName;
    },
    search() {
      window.location.href = getUrl(this.init.href.current, this.keys);
    },
    toEdit(id) {
      window.location = getUrl(this.init.href.save, {id: id});
    },
    editStatus(obj) {
      var $btn = $(obj.event.currentTarget).loading('loading');

      axios.post(this.init.href.current, {
        action: 'status',
        id: obj.data.id,
        status: obj.value
      }).then(function (response) {
        if (response.status) {
          obj.data.status = obj.value;
        } else {
          alert(response.msg);
        }
        $btn.loading('reset');
      });
    },
    remove(event, id, inx) {
      if (!confirm('Are you sure to delete this item?')) {
        return false;
      }

      var $btn = $(event.currentTarget).loading('<i class="fas fa-spinner fa-spin"></i>');
      var _this = this;
      axios.post(this.init.href.current, {
        action: 'remove',
        id: id
      }).then(function (response) {
        if (response.data.status) {
          _this.init.list.splice(inx, 1);
        } else {
          alert(response.data.msg);
        }
        $btn.loading('reset');
      });
    }
  }
});
</script>
@endsection
