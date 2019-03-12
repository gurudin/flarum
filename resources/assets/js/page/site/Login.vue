<template>
<div class="login">
  <div class="modal fade show" style="display: block" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">{{$t('inOrUp.sign-in')}}</h5>
          <button type="button" class="close" @click="goBack">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form>
            <div class="form-group">
              <label>{{$t('inOrUp.email')}}</label>
              <input type="text"
                class="form-control"
                :class="{'is-invalid':errors.email}"
                v-model.trim="m.email"
                required
                autofocus
                :placeholder="$t('inOrUp.enter-email')">
              <div class="invalid-feedback">
                {{errors.email}}
              </div>
            </div>

            <div class="form-group">
              <label>{{$t('inOrUp.password')}}</label>
              <input type="password"
                class="form-control"
                :class="{'is-invalid':errors.password}"
                v-model.trim="m.password"
                required
                :placeholder="$t('inOrUp.password')">
              <div class="invalid-feedback">
                {{errors.password}}
              </div>
            </div>

            <div class="form-group">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" v-model="m.remember_me" id="remember">
                <label class="form-check-label text-muted" for="remember">
                  <small>{{$t('inOrUp.remember-me')}}</small>
                </label>
              </div>
            </div>

            <div class="form-group row">
              <div class="col-10 m-auto">
                <button type="button"
                  class="btn btn-lg text-white btn-block def-bg-color"
                  :disabled="isVaild"
                  @click="signIn">{{$t('inOrUp.sign-in')}}</button>
              </div>
            </div>

            <p class="text-muted text-center">
              <small>
                {{$t('inOrUp.dont-have-an-account')}} <router-link :to="{name: 'register', params: {source: $route.params.source}}" class="def-color">{{$t('inOrUp.sign-up')}}</router-link>
              </small>
            </p>
          </form>
        </div>
        <div class="modal-footer"></div>
      </div>
    </div>
  </div>

  <div class="modal-backdrop fade in" style="opacity: 0.5;"></div>
</div>
</template>

<script>
export default {
  name: 'login',
  data() {
    return {
      m: {
        email: '',
        password: '',
        remember_me: ''
      },
      errors: {},
    };
  },
  computed: {
    isVaild() {
      if (this.m.email == ''
        || this.m.password == ''
        || this.m.password.length < 6
      ) {
        return true;
      }

      return false;
    }
  },
  methods: {
    goBack() {
      if (!this.$route.params.source) {
        this.$route.params.source = 'home';
      }

      this.$router.push({name: this.$route.params.source});
    },
    signIn(event) {
      this.errors = {};
      var $btn = $(event.currentTarget).loading('loading...');
      var _this = this;

      this.GLOBAL.api.login(this.m, function (rep) {
        if (rep.status) {
          if (rep.code == -1) {
            _this.errors = rep.data;
          } else {
            _this.alert('alert-success', _this.$t('inOrUp.sign-in-success'));

            rep.data.expiration_time = _this.moment().add(7, 'days').format('YYYY-MM-DD HH:mm:ss');
            window.localStorage.setItem('user', JSON.stringify(rep.data));
            
            setTimeout(() => {
              window.location = '/';
            }, 1500);
          }
        } else {
          _this.alert('alert-warning', rep.msg);
        }
        $btn.loading('reset');
      });
    }
  }
}
</script>
