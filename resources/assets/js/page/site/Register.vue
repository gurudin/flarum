<template>
<div class="login">
  <div class="modal fade show" style="display: block" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">{{$t('inOrUp.sign-up')}}</h5>
          <button type="button" class="close" @click="goBack">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form>
            <div class="form-group">
              <label>{{$t('inOrUp.nickname')}} <small class="text-danger">*</small></label>
              <input type="text"
                class="form-control"
                :class="{'is-invalid':errors.nickname}"
                required
                autofocus
                :placeholder="$t('inOrUp.enter-nickname')"
                v-model.trim="m.nickname">
              <div class="invalid-feedback">
                {{errors.nickname}}
              </div>
            </div>

            <div class="form-group">
              <label>{{$t('inOrUp.email')}} <small class="text-danger">*</small></label>
              <input type="text"
                class="form-control"
                :class="{'is-invalid':errors.email}"
                required
                :placeholder="$t('inOrUp.enter-email')"
                v-model.trim="m.email">
              <div class="invalid-feedback">
                {{errors.email}}
              </div>
            </div>

            <div class="form-group">
              <label>{{$t('inOrUp.password')}} <small class="text-danger">*</small></label>
              <input type="password"
                class="form-control"
                :class="{'is-invalid':errors.password}"
                required
                :placeholder="$t('inOrUp.enter-password')"
                v-model.trim="m.password">
              <div class="invalid-feedback">
                {{errors.password}}
              </div>
            </div>

            <div class="form-group">
              <label>{{$t('inOrUp.confirm-password')}} <small class="text-danger">*</small></label>
              <input type="password"
                class="form-control"
                required
                :placeholder="$t('inOrUp.confirm-password')"
                v-model.trim="m.password_confirmation">
            </div>

            <div class="form-group">
              <label>{{$t('inOrUp.inviter-code')}}</label>
              <input type="text"
                class="form-control col-6"
                :class="{'is-invalid':errors.shareCode}"
                v-model.trim="m.shareCode"
                :placeholder="$t('inOrUp.inviter-code')">
              <div class="invalid-feedback">
                {{errors.shareCode}}
              </div>
            </div>

            <div class="form-group row">
              <div class="col-10 m-auto">
                <button type="button"
                  class="btn btn-lg text-white btn-block def-bg-color"
                  :disabled="isVaild"
                  @click="signUp">{{$t('inOrUp.sign-up')}}</button>
              </div>
            </div>

            <p class="text-muted text-center">
              <small>
                {{$t('inOrUp.already-have-an-account')}} <router-link :to="{name: 'login', params: {source: $route.params.source}}" class="def-color">{{$t('inOrUp.sign-in')}}</router-link>
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
        nickname: '',
        email: '',
        password: '',
        password_confirmation: '',
        shareCode: '',
      },
      errors: {},
    };
  },
  computed: {
    isVaild() {
      if (this.m.nickname == ''
        || this.m.email == ''
        || this.m.password == ''
        || this.m.password_confirmation == ''
        || this.m.nickname.length < 4
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
    signUp(event) {
      var pattern = /^([A-Za-z0-9_\-\.\u4e00-\u9fa5])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,8})$/;
      if (!pattern.test(this.m.email)) {
        this.alert('alert-warning', this.$t('inOrUp.email-error'));
        return false;
      }

      if (this.m.password != this.m.password_confirmation) {
        this.alert('alert-warning', this.$t('inOrUp.the-passwords-are-different'));
        return false;
      }
      
      this.errors = {};
      var $btn = $(event.currentTarget).loading('loading...');
      var _this = this;
      this.GLOBAL.api.regisger(this.m, function (rep) {
        if (rep.status) {
          if (rep.code == -1) {
            _this.errors = rep.data;
          } else {
            _this.alert('alert-success', '注册成功');
            setTimeout(() => {
              _this.$router.push({name: 'login'});
            }, 1000);
          }
        } else {
          _this.alert('alert-warning', rep.msg);
        }
        $btn.loading('reset');
      });
    }
  },
  created() {
    if (this.$route.query.share) {
      this.m.shareCode = this.$route.query.share;
    }
  }
}
</script>
