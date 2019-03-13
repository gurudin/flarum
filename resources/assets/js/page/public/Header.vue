<template>
  <header class="w-100 bg-light">
    <div class="alert" id="alert" style="display: none; width: 100%; position: absolute; top: 0; z-index: 999999;">
      <span id="alertContent">Holy guacamole!</span>
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    
    <div class="clearfix" style="height: 56px;"></div>
    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top border-bottom">
      <a class="navbar-brand d-none d-xl-block" href="/">Gurudin</a>

      <!-- Left menu -->
      <a href="#" v-if="$route.name != 'detail'" class="d-block d-xl-none def-color" data-toggle="collapse" data-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></a>
      <button v-if="$route.name == 'detail'" @click="goBack" class="d-block d-xl-none def-color" style="border: none; background: #f8f9fa;"><i class="fas fa-chevron-left"></i></button>
      <!-- /Left menu -->

      <!-- Center button -->
      <button @click="togglePanel = !togglePanel" v-if="$route.name != 'detail'" class="btn btn-sm bg-white d-block d-xl-none def-color" style="border: 1px solid #667c99;" type="button">
        {{$t('all-posts')}} <i class="fas fa-sort"></i>
      </button>
      <!-- /Center button -->
      
      <!-- Create post -->
      <a class="pointer d-block d-xl-none def-color" @click="sendPost">
        <i class="far fa-edit"></i>
      </a>
      <!-- /Create post -->

      <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
        <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
          <li class="nav-item active">
            <a class="nav-link" href="/">{{$t('home')}} <span class="sr-only">(current)</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link pointer" @click="invite">{{$t('invite')}}</a>
          </li>
        </ul>
        <form class="form-inline my-2 my-lg-0">
          <!-- <div class="input-group mr-3">
            <input type="text" class="form-control form-control-sm border-right-0" :placeholder="$t('search-forum')">
            <div class="input-group-append">
              <button class="btn btn-sm btn-search" type="button"><i class="fas fa-search"></i></button>
            </div>
          </div> -->
        </form>

        <ul class="navbar-nav  justify-content-end">
          <li class="nav-item" v-if="typeof localStorage.user != 'undefined'">
            <!-- xl -->
            <div class="btn-group d-none d-xl-block" role="group">
              <button id="in-login-btn" type="button" class="btn in-login-btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <img :src="JSON.parse(localStorage.user).avatar" width="25" height="25" class="rounded-circle">&nbsp;
                <span>{{JSON.parse(localStorage.user).name}}</span>
              </button>
              <div class="dropdown-menu dropdown-menu-right" aria-labelledby="in-login-btn">
                <a class="dropdown-item text-dark font13 pointer" @click="">
                  <i class="fas fa-user-cog"></i>&nbsp;&nbsp;&nbsp;&nbsp;{{$t('user-profile')}}
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item text-dark font13 pointer" @click="signOut">
                  <i class="fas fa-sign-out-alt"></i>&nbsp;&nbsp;&nbsp;&nbsp;{{$t('sign-out')}}
                </a>
              </div>
            </div>

            <!-- small -->
            <span class="d-block d-xl-none">
              <button class="btn in-login-btn">
                <img :src="JSON.parse(localStorage.user).avatar" width="25" height="25" class="rounded-circle">&nbsp;
                <span>{{JSON.parse(localStorage.user).name}}</span>
              </button>

              <!-- <a class="dropdown-item text-dark font13 pointer" @click="">
                <i class="fas fa-user-cog"></i>&nbsp;&nbsp;&nbsp;&nbsp;{{$t('user-profile')}}
              </a> -->
              <a class="dropdown-item text-dark font13 pointer" @click="signOut">
                <i class="fas fa-sign-out-alt"></i>&nbsp;&nbsp;&nbsp;&nbsp;{{$t('sign-out')}}
              </a>
            </span>
          </li>

          <li class="nav-item" v-if="typeof localStorage.user == 'undefined'">
            <router-link
              class="nav-link font13"
              :to="{name: to.register, params: {source: $route.name}}">{{$t('register')}}</router-link>
          </li>
          <li class="nav-item" v-if="typeof localStorage.user == 'undefined'">
            <router-link
              class="nav-link font13"
              :to="{name: to.login, params: {source: $route.name}}">{{$t('login')}}</router-link>
          </li>
        </ul>
      </div>
    </nav>

    <transition name="slide-fade">
      <div class="toggle-panel" v-if="togglePanel">
        <div class="drawer-backdrop" @click="togglePanel = !togglePanel"></div>
        <div class="panel border-right">
          <app-left></app-left>
        </div>
      </div>
    </transition>

  </header>
</template>

<script>
import appLeft from "./LeftSm";

export default {
  name: "appHeader",
  data() {
    return {
      to: {
        login: 'login',
        register: 'register'
      },
      togglePanel: false,
    };
  },
  components: {
    appLeft,
  },
  methods: {
    goBack() {
      this.$router.go(-1);
    },
    invite() {
      this.alert('alert-warning', this.$t('unopen'));
    },
    sendPost() {
      this.alert('alert-warning', this.$t('unopen'));
    },
    signOut() {
      var _this = this;
      this.GLOBAL.api.logout({}, function (rep) {
        _this.localStorage.removeItem('user');
        _this.alert('alert-success', _this.$t('sign-out-success'));

        setTimeout(() => {
          window.location = '/';
        }, 1500);
      });
    }
  },
  created() {
    switch (this.$route.name) {
      case 'detail':
        this.to = {login: 'd-login', register: 'd-register'};
        break;
    
      default:
        this.to = {login: 'login', register: 'register'};
        break;
    }
  }
}
</script>

<style scoped>
.btn-search {
  border-bottom-color: #ced4da;
  border-top-color: #ced4da;
  border-right-color: #ced4da;
  background-color: #ffffff;
}
.in-login-btn {
  padding-left: 2px !important;
  padding-right: 2px !important;
  color: rgba(0, 0, 0, 0.5)
}
.in-login-btn:hover {
  background: #d7dfea;
}
.toggle-panel {
  position: fixed;
  top: 0;
  z-index: 9999;
  width: 100%;
  height: 100%;
}
.toggle-panel .drawer-backdrop {
  width: 100%;
  height: 100%;
  opacity: 0.5;
  background: black;
}
.toggle-panel .panel {
  width: 65%;
  height: 100%;
  top: 0;
  position: absolute;
  background: white;
}
.pointer {
  cursor: pointer;
}

.slide-fade-enter-active {
  transition: all .2s ease;
}
.slide-fade-leave-active {
  transition: all .2s cubic-bezier(1.0, 0.5, 0.8, 1.0);
}
.slide-fade-enter, .slide-fade-leave-to
/* .slide-fade-leave-active for below version 2.1.8 */ {
  transform: translateX(-10px);
  opacity: 0;
}
</style>

