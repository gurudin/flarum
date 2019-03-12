@extends('layouts.admin')

@section('title') Invite Code @endsection

@section('css')

@endsection

@section('content')
<div class="card">
  <div class="card-body">
    <h4 class="card-title">Invite Code
      <button class="btn btn-outline-success float-right" data-toggle="modal" data-target="#createModal">
        <i class="fas fa-file-signature"></i> Create
      </button>
    </h4>
    <hr>

    <form>
      <div class="form-row">
        <div class="col-md-3 mb-3">
          <label>Status</label>
          <select class="form-control" v-model="init.keys.status">
            <option value="">All</option>
            <option value="used">Has been used</option>
            <option value="expired">Has expired</option>
          </select>
        </div>

        <div class="col-md-3 mb-3">
          <label>User ID</label>
          <input type="number" class="form-control" v-model="init.keys.uid" placeholder="Enter User ID">
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
          <th scope="col">VIP valid time</th>
          <th scope="col">Code</th>
          <th scope="col">Expiration at</th>
          <th>Created at</th>
          <th>Action</th>
        </tr>
      </thead>

      <tbody>
        <tr
          v-for="(item,inx) in init.inviteCodes"
          :class="returnClass(item)">
          
          <th>@{{item.id}}</th>
          <td>
            @{{item.name}}
            <small v-if="item.fk_user_id" class="text-muted"><br>
              @{{moment(item.activation_at).format('YYYY/MM/DD')}}
                - 
              @{{moment(item.activation_at).add(item.vip_valid, 'months').format('YYYY/MM/DD')}}
            </small>
          </td>
          <td>@{{item.vip_valid}} months</td>
          <td>@{{item.code}}</td>
          <td>
            @{{moment(item.code_expiration_at).format('YYYY/MM/DD HH:mm')}}
            <small v-if="item.code_expiration_at < moment().format('YYYY-MM-DD HH:mm:ss')" class="text-muted"><br>Has expired</small>
          </td>
          <td>@{{moment(item.created_at).format('YYYY/MM/DD HH:mm')}}</td>
          <td>
            <button
              type="button"
              class="btn btn-danger btn-sm"
              :disabled="item.fk_user_id"
              @click="remove($event, item.id, inx)">
              <i class="fas fa-trash-alt"></i></button>
          </td>
        </tr>
      </tbody>
    </table>

    <div>
      <div class="float-left">{{$invite_codes->total()}} entries</div>
      <div class="float-right">
        {{ $invite_codes->links() }}
      </div>
    </div>
  </div>
</div>

{{-- Create modal --}}
<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Create invite code</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form>
          <div class="form-group">
            <label class="col-form-label">Number *</label>
            <input type="number" class="form-control" v-model="init.m.number">
          </div>
          <div class="form-group">
            <label class="col-form-label">Invitation code expiration time</label>
            <select class="form-control" v-model="init.m.expiration_day">
              <option value="1">1 day</option>
              <option value="2">2 days</option>
              <option value="3">3 days</option>
              <option value="4">4 days</option>
              <option value="5">5 days</option>
              <option value="6">6 days</option>
              <option value="7">7 days</option>
            </select>
          </div>
          <div class="form-group">
            <label class="col-form-label">VIP valid time</label>
            <select class="form-control" v-model="init.m.valid_month">
              <option value="1">1 month</option>
              <option value="3">3 months</option>
              <option value="6">6 months</option>
              <option value="12">1 year</option>
            </select>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button"
          class="btn btn-success"
          :disabled="init.m.number == ''"
          @click="createInviteCode">Create invite code</button>
      </div>
    </div>
  </div>
</div>
{{-- /Create modal --}}
@endsection

@section('script')
<script>
const vm = new Vue({
  el: "#app",
  data() {
    return {
      init: {
        href: {
          current: "{{route('admin.users.code')}}",
        },
        keys: @json($search),
        inviteCodes: @json($codes),
        m: {
          number: 1,
          expiration_day: 1,
          valid_month: 1
        },
      },
    };
  },
  methods: {
    returnClass(item) {
      if (item.fk_user_id) {
        return 'table-success';
      }
      if (item.code_expiration_at < moment().format('YYYY-MM-DD HH:mm:ss')) {
        return 'table-danger';
      }
    },
    search() {
      window.location.href = getUrl(this.init.href.current, this.init.keys);
    },
    createInviteCode($event) {
      var $btn = $(event.currentTarget).loading('<i class="fas fa-spinner fa-spin"></i> Loading');

      axios.post(this.init.href.current, {
        action: 'create',
        data: this.init.m
      }).then((rep) =>{
        if (rep.data.status) {
          window.location.reload();
        }
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
          _this.init.inviteCodes.splice(inx, 1);
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
