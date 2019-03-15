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
          <th scope="col">#</th>
          <th scope="col">推荐类别</th>
          <th scope="col">位置</th>
          <th scope="col">封面</th>
          <th scope="col">描述</th>
          <th>状态</th>
          <th>操作</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(item,inx) in init.recomments">
          <th scope="row">@{{item.id}}</th>
          <td>@{{init.typeOptions[item.type]}}</td>
          <td>@{{init.positions[item.position]}}</td>
          <td>
            <a :href="item.cover" target="_blank" v-if="item.cover != ''">
              <img :src="item.cover" class="rounded" width="35">
            </a>
          </td>
          <td>@{{item.remark}}</td>
          <td>
            <toggle-button
              :data="item"
              v-model="item.status"
              size="sm"
              :options="options"
              :before="editEnabled"></toggle-button>
          </td>
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
        {{ $recomments->links() }}
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
        categorys: @json($categorys),
        recomments: @json($recomments).data,
        positions: @json($positions),
        typeOptions: @json($type_options),
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
    toEdit(id) {
      window.location = getUrl(this.init.href.save, {id: id});
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
          _this.init.recomments.splice(inx, 1);
        } else {
          alert(response.data.msg);
        }
        $btn.loading('reset');
      });
    },
    editEnabled(obj) {
      axios.post(this.init.href.current, {
        action: 'enabled',
        id: obj.data.id,
        enabled: obj.value
      }).then(function (response) {
        if (response.status) {
          obj.data.enabled = obj.value;
        } else {
          alert(response.msg);
        }
      });
    },
  }
});
</script>
@endsection
