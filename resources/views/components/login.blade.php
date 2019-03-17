{{-- @if ($blade == 'login') --}}
<div class="login" id="login" v-if="show" v-cloak>
  <div tabindex="-1" role="dialog" aria-hidden="true" class="modal fade show" style="display: block;">
    <div role="document" class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">@lang('frontend.inOrUp.sign-in')</h5>
          {{-- <a href="{{url()->current()}}" class="close"><span aria-hidden="true">×</span></a> --}}
          <a @click="show = false" class="close pointer"><span aria-hidden="true">×</span></a>
        </div>

        <div class="modal-body">
          <form>
            <div class="form-group">
              <label>@lang('frontend.inOrUp.email')</label>
              <input type="text"
                v-model.trim="m.email"
                :class="{'is-invalid':errors.email}"
                required="required"
                autofocus="autofocus"
                placeholder="@lang('frontend.inOrUp.enter-email')"
                class="form-control">
              <div class="invalid-feedback">@{{errors.email}}</div>
            </div>
            <div class="form-group">
              <label>@lang('frontend.inOrUp.password')</label>
              <input type="password"
                v-model.trim="m.password"
                :class="{'is-invalid':errors.password}"
                @keyup.enter="login"
                required="required"
                placeholder="@lang('frontend.inOrUp.password')"
                class="form-control">
              <div class="invalid-feedback">@{{errors.password}}</div>
            </div>
            <div class="form-group">
              <div class="form-check">
                <input type="checkbox" id="remember" v-model="m.remember_me" class="form-check-input">
                <label for="remember" class="form-check-label text-muted">
                  <small>@lang('frontend.inOrUp.remember-me')</small>
                </label>
              </div>
            </div>
            
            <div class="form-group row">
              <div class="col-10 m-auto">
                <button type="button" @click="login" ref="login" class="btn btn-lg text-white btn-block def-bg-color">@lang('frontend.inOrUp.sign-in')</button>
              </div>
            </div>
            <p class="text-muted text-center">
              <small>@lang('frontend.inOrUp.dont-have-an-account') <a @click="show = false, register.show = true" class="def-color pointer">@lang('frontend.inOrUp.sign-up')</a></small>
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
const login = new Vue({
  el: "#login",
  data() {
    return {
      m: {
        email: '',
        password: '',
        remember_me: false,
      },
      errors: {},
      show: false,
    };
  },
  methods: {
    login(event) {
      var $btn = $(this.$refs.login).loading('loading...');

      var _this = this;
      _this.errors = {};
      axios.post("{{route('frontend.login')}}", this.m).then(function (response) {
        return response.data;
      }).then(function (rep) {
        if (rep.status) {
          window.location.href = "{{url(url()->current())}}";
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
{{-- @endif --}}
