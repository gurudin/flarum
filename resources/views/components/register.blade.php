<div class="register" id="register" v-if="show" v-cloak>
  <div tabindex="-1" role="dialog" aria-hidden="true" class="modal fade show" style="display: block;">
    <div role="document" class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">@lang('frontend.inOrUp.sign-up')</h5>
          <a @click="show = false" class="close pointer"><span aria-hidden="true">×</span></a>
        </div>
        
        <div class="modal-body">
          <form>
            <div class="form-group">
              <label>@lang('frontend.inOrUp.nickname') <small class="text-danger">*</small></label>
              <input type="text"
                required="required"
                autofocus="autofocus"
                placeholder="@lang('frontend.inOrUp.enter-nickname')"
                class="form-control"
                :class="{'is-invalid':errors.nickname}"
                v-model.trim="m.nickname">
              <div class="invalid-feedback">@{{errors.nickname}}</div>
            </div>
            
            <div class="form-group">
              <label>@lang('frontend.inOrUp.email') <small class="text-danger">*</small></label>
              <input type="text"
                required="required"
                placeholder="@lang('frontend.inOrUp.enter-email')"
                class="form-control"
                :class="{'is-invalid':errors.email}"
                v-model.trim="m.email">
              <div class="invalid-feedback">@{{errors.email}}</div>
            </div>
            
            <div class="form-group">
              <label>@lang('frontend.inOrUp.password') <small class="text-danger">*</small></label>
              <input type="password"
                required="required"
                placeholder="@lang('frontend.inOrUp.enter-password')"
                class="form-control"
                :class="{'is-invalid':errors.password}"
                v-model.trim="m.password">
              <div class="invalid-feedback">@{{errors.password}}</div>
            </div>
            
            <div class="form-group">
              <label>@lang('frontend.inOrUp.confirm-password') <small class="text-danger">*</small></label>
              <input type="password"
                required="required"
                placeholder="@lang('frontend.inOrUp.confirm-password')"
                class="form-control"
                v-model.trim="m.password_confirmation">
            </div>
            
            <div class="form-group">
              <label>@lang('frontend.inOrUp.inviter-code')</label>
              <input type="text"
                placeholder="@lang('frontend.inOrUp.inviter-code')"
                class="form-control col-6"
                :class="{'is-invalid':errors.shareCode}"
                v-model.trim="m.shareCode">
              <div class="invalid-feedback">@{{errors.shareCode}}</div>
            </div>
            
            <div class="form-group row">
              <div class="col-10 m-auto">
                <button type="button" @click="register" class="btn btn-lg text-white btn-block def-bg-color">@lang('frontend.inOrUp.sign-up')</button>
              </div>
            </div>
            
            <p class="text-muted text-center">
              <small>@lang('frontend.inOrUp.already-have-an-account') <a @click="show = false, login.show = true" class="def-color pointer">@lang('frontend.inOrUp.sign-in')</a></small>
            </p>
          </form>
        </div>
        
        <div class="modal-footer"></div>
      </div>
    </div>
  </div>
  <div class="modal-backdrop fade in" style="opacity: 0.5;"></div>
</div>

<script>
const register = new Vue({
  el: "#register",
  data() {
    return {
      m: {
        nickname: '',
        email: '',
        password: '',
        password_confirmation: '',
        shareCode: '',
      },
      errors: {},
      show: false,
    };
  },
  methods: {
    register(event) {
      var $btn = $(event.currentTarget).loading('loading...');

      var _this = this;
      _this.errors = {};
      axios.post("{{route('frontend.register')}}", this.m).then(function (response) {
        return response.data;
      }).then(function (rep) {
        if (rep.status) {
          // window.location.href = "{{url(url()->current() . '?blade=login')}}";
          _this.show = false;
          login.show = true;
        } else {
          if (rep.code == -1) {
            _this.errors = rep.data;
          } else {
            window.toast(rep.msg, 'warning');
          }
        }
        
        $btn.loading('reset');
      });
    }
  }
});
</script>
