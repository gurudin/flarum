<template>
  <div id="detail">
    <app-header></app-header>

    <div class="content row h-100">
      <div class="col-lg-2 d-none d-xl-block content-left">
        <!-- <p class="text-muted mb-0 font12">封面</p> -->
        <img :src="post.cover" alt="cover" class="img-thumbnail">
        <p class="text-muted mt-0 font12"><i class="fa fa-eye"></i> {{post.pv}} | <i class="fa fa-coins"></i> {{post.coin}}</p>

        <button type="button" class="btn def-bg-color text-white btn-block btn-sm">{{$t('reply-post')}}  <i class="fas fa-reply"></i></button>

        <!-- <p class="left-p margin-25 active"><i class="icon far fa-comments"></i> {{$t('all_discussions')}}</p>
        <p class="left-p"><i class="icon fas fa-th-large"></i> {{$t('tags')}}</p>

        <p class="border-bottom"></p>

        <p class="left-p margin-25">
          <i class="icon rounded" style="background-color: rgb(103, 204, 234);">&nbsp;&nbsp;&nbsp;&nbsp;</i> asdad
        </p> -->
      </div>

      <div class="col-lg-10 col-sm-12 content-right" v-if="readMsg == ''">
        <div class="p-3 clearfix text-center text-white" :style="bg">
          <h4>{{post.title}}</h4>
        </div>

        <ul class="list-group list-group-flush">
          <li class="list-group-item col-12">
            <div class="row">
              <div class="col-lg-1 d-none d-xl-block">
                <img :src="post.avatar" width="60" class="float-left rounded-circle" alt="avatar">
              </div>
              <div class="col-lg-11 col-sm-12">
                <p class="align-middle">
                  <img :src="post.avatar" width="40" class="rounded-circle mr-2 ml-0 d-xl-none" alt="avatar">
                  <span class="font-weight-bold">{{post.nickname}}</span>
                  <small class="ml-2">
                    <a href="" class="def-color">{{moment(post.created_at * 1000).format("YYYY 'MM")}}</a>
                  </small>
                </p>

                <hr class="text-muted">

                <div class="content-box">
                  <span v-html="post.content" >
                  </span>
                </div>
              </div>
            </div>
          </li>
          
          <!-- <li class="list-group-item col-12">
            <div class="float-left mr-3 d-none d-xl-block">
              <img src="http://discuss.flarum.org.cn/assets/avatars/5u8fismsy89tqlxn.jpg" width="60" class="float-left rounded-circle" alt="...">
            </div>
            <div>
              <p><span class="font-weight-bold">admin</span> <small class="ml-2"><a href="#" class="def-color">Aug '15</a></small></p>
              <p style="display: flex;">
                如果老子当了码工，那么他一定会设计一种非常宽松的程序设计语言，你甚至不需要声明一个变量，因为你根本不可能准确地定义什么是变量。这门语言非常奥妙，对于懂的人来说，它是那么地神奇，可以解决天下任何问题，而对于不懂的人来说，它简直就是一堆乱码。
              </p>
            </div>
          </li>
          <li class="list-group-item col-12">
            <div class="float-left mr-3 d-none d-xl-block">
              <img src="http://discuss.flarum.org.cn/assets/avatars/5u8fismsy89tqlxn.jpg" width="60" class="float-left rounded-circle" alt="...">
            </div>
            <div>
              <p><span class="font-weight-bold">admin</span> <small class="ml-2"><a href="#" class="def-color">Aug '15</a></small></p>
              <p style="display: flex;">
                如果孔子当了码工，那么他一定会发明一种新的程序设计语言，这门语言庄重典雅，格式规范。他会告诉你越早的程序设计语言才是越好的。他会培养众多的弟子，但因为大公司没有一个愿意使用这门语言，他只能带着诸多弟子颠沛流离，以干咨询为生。
              </p>
            </div>
          </li> -->
        </ul>
      </div>

      <div class="col-lg-10 col-sm-12 content-right" v-if="readMsg != ''">
        <p class="text-muted"><i class="fas fa-exclamation-triangle"></i> {{readMsg}}</p>
      </div>
    </div>

    <!-- Children route -->
    <!-- <transition name="slide-fade"> -->
      <router-view/>
    <!-- </transition> -->
    <!-- /Children route -->
  </div>
</template>

<script>
import appHeader from "./public/Header";
import store from "../store/index";

export default {
  name: 'detail',
  data() {
    return {
      post: {},
      comments: [],
      readMsg: '',
    };
  },
  components: {
    appHeader
  },
  computed: {
    bg() {
      let bg = {'background-color': '#6c757d !important'};
      if (this.post.cid && store.state.category.categorys.length > 0) {
        store.state.category.categorys.forEach(row =>{
          if (row.id == this.post.cid) {
            bg = {'background-color': row.color + ' !important'};
          }
        });
      }
      
      return bg;
    }
  },
  methods: {
  },
  created() {
    var _this = this;
    this.GLOBAL.api.getPostById(this.$route.params.id, function (rep) {
      if (rep.status) {
        if (rep.code == 1001 || rep.code == 1002) {
          _this.readMsg = rep.msg;
          _this.$router.push({name: 'd-login', params: {source: 'detail'}});
        }

        if (rep.code == 0) {
          _this.post = rep.data.post;
          _this.comments = rep.data.comments;
        }
      } else {
        _this.readMsg = rep.msg;
        _this.alert('alert-warning', '<i class="fas fa-exclamation-triangle"></i> ' + rep.msg);
      }
    });
  }
}
</script>

<style>
.list-group li {
  padding-left: 5px !important;
  padding-right: 5px !important;
  color: #333;
}
.content-box img {
  width: 100% !important;
}
</style>

