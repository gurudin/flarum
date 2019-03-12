@extends('layouts.admin')

@section('title') Users @endsection

@section('css')

@endsection

@section('content')
<div class="card">
  <div class="card-body">
    <h4 class="card-title">Users
      <button class="btn btn-outline-success float-right" data-toggle="modal" data-target="#createModal">
        <i class="fas fa-file-signature"></i> Create
      </button>
    </h4>
    <hr>

    <form>
      <div class="form-row">
        <div class="col-md-2 mb-2">
          <label>Status</label>
          <select class="form-control" v-model="init.keys.status">
            <option value="">All</option>
            <option value="normal">Normal</option>
            <option value="blacklist">Blacklist</option>
          </select>
        </div>

        <div class="col-md-2 mb-2">
          <label>Whether a vip</label>
          <select class="form-control" v-model="init.keys.vip">
            <option value="">All</option>
            <option value="yes">Yes</option>
            <option value="no">No</option>
          </select>
        </div>

        <div class="col-md-3 mb-3">
          <label>Name or Email</label>
          <input type="text" class="form-control" v-model="init.keys.key" placeholder="Enter name or email">
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
          <th scope="col">Username</th>
          <th scope="col">Email</th>
          <th scope="col">VIP</th>
          <th scope="col">Coins</th>
          <th scope="col">Inviter</th>
          <th scope="col">Status</th>
          <th>Created at</th>
        </tr>
      </thead>

      <tbody>
        <tr v-for="item in init.users">
          <th>@{{item.id}}</th>
          <td>@{{item.name}}</td>
          <td>@{{item.email}}</td>
          <td>
            <span v-if="item.vip_start_at && item.vip_end_at">
              @{{moment(item.vip_start_at).format('YYYY/MM/DD HH:mm')}} - @{{moment(item.vip_end_at).format('YYYY/MM/DD HH:mm')}}
            </span>
            <span class="text-muted" v-else>(not vip)</span>
          </td>
          <td>@{{item.coins}}</td>
          <td>@{{item.share_code}}</td>
          <td>
            <span v-if="item.status==10">Administrator</span>
            <toggle-button
              v-if="item.status!=10"
              :data="item"
              v-model="item.status"
              size="sm"
              :options="options"
              :before="forbided"></toggle-button>
          </td>
          <td>@{{moment(item.created_at).format('YYYY/MM/DD HH:mm')}}</td>
        </tr>
      </tbody>
    </table>

    <div>
      <div class="float-left">{{$users->total()}} entries</div>
      <div class="float-right">
        {{ $users->links() }}
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
          current: "{{route('admin.users.list')}}",
        },
        users: @json($users).data,
        keys: @json($search),
      },
      options: [
        {label: "Active", value: 1, checked: "success"},
        {label: "Forbid", value: 0, checked: "danger"}
      ],
    };
  },
  methods: {
    search() {
      window.location.href = getUrl(this.init.href.current, this.init.keys);
    },
    forbided(obj) {
      var $btn = $(obj.event.currentTarget).loading('loading');

      axios.post(this.init.href.current, {
        action: 'forbid',
        id: obj.data.id,
        status: obj.value
      }).then(function (response) {
        if (response.data.status) {
          obj.data.status = obj.value;
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
