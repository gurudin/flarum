<template>
<div id="main">
  <app-header></app-header>

  <div class="content row h-100">
    <app-left></app-left>

    <div class="col-lg-10 col-sm-12 content-right">
      <div class="px-1 control-bar clearfix">
        <div class="float-left">
          <select class="custom-select custom-select-sm def-color"
            v-model="searchKey.order"
            style="border: 1px solid #667c99;"
            @change="refreshArticle">
            <option value="new">{{$t('latest-posts')}}</option>
            <option value="top">{{$t('top-posts')}}</option>
          </select>
        </div>

        <div class="float-right">
          <button type="button" class="btn btn-light def-color" @click="refreshArticle"><i class="fas fa-redo-alt"></i></button>
        </div>
      </div>

      <div class="control-body mt-2" v-if="!articleItem.item">
        <p class="text-center text-muted"><i class="fa fa-spin fa-spinner"></i></p>
      </div>

      <div class="control-body mt-2" v-if="articleItem.item">

        <div class="card-columns">
          <div class="card" v-for="post in articleItem.item">
            <a class="pointer" @click="toDetail(post)">
              <img class="card-img-top p-1" :src="post.cover" alt="cover">
            </a>
            <a class="card-text p-1 d-flex pointer text-secondary" @click="toDetail(post)">
              {{post.title}}
            </a>
            <p class="card-text text-right p-1">
              <small>
                <span class="p-1 text-muted"><i class="fa fa-eye"></i> {{post.pv}}</span>
                <span class="p-1 text-muted"><i class="fa fa-coins"></i> {{post.coin}}</span>
              </small>
            </p>
          </div>
        </div>

        <p class="text-center mt-5 mb-5" v-if="articleItem.page.next">
          <button type="button" class="btn btn-load-more col-md-4 col-sm-12" @click="nextPage">
            {{$t('load-more')}}
          </button>
        </p>
      </div>
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
import appLeft from "./public/Left";
import store from "../store/index";
import { mapState, mapActions } from "vuex";

export default {
  name: "Main",
  data() {
    return {
    };
  },
  components: {
    appHeader,
    appLeft,
  },
  computed: mapState({
    articleItem: state => state.article.articleItem,
    searchKey: state => state.article.searchKey,
  }),
  methods: {
    ...mapActions(["getArticle", "refreshArticle"]),
    _isMobile() {
      let flag = navigator.userAgent.match(/(phone|pad|pod|iPhone|iPod|ios|iPad|Android|Mobile|BlackBerry|IEMobile|MQQBrowser|JUC|Fennec|wOSBrowser|BrowserNG|WebOS|Symbian|Windows Phone)/i)
      return flag;
    },
    nextPage(event) {
      var $btn = $(event.currentTarget).loading(this.$t('load-more') + ' <i class="fa fa-spin fa-spinner"></i>');
      var oldLength = this.articleItem.item.length;
      store.state.article.searchKey.page += 1;
      
      this.getArticle();

      setTimeout(() => {
        $btn.loading('reset');
      }, 1500);
    },
    toDetail(post) {
      if (post.read_level == 0) {
        this.$router.push({name: 'detail', params: {id: post.pid}});
        return false;
      }
      
      if (typeof localStorage.user == 'undefined') {
        this.$router.push({name: 'login', params: {source: this.$route.name}});
      } else {
        if (post.read_level == 1) {
          this.$router.push({name: 'detail', params: {id: post.pid}});
          return false;
        }

        alert('post.read_level:' + post.read_level);
      }
    }
  },
  created() {
    if (!store.state.article.articleItem.item) {
      this.getArticle();
    }
  }
}
</script>


<style scoped>
.card img {
  filter: brightness(.8);
}
.card img:hover {
  transition: all .3s cubic-bezier(1.0, 0.5, 0.8, 1.0);
  filter: brightness(1);
}
.btn-load-more {
  border: none;
  color: #667c99;
  background: #e8ecf3;
  font-size: 13px;
}
.btn-load-more:hover, .btn-load-more:focus, .btn-load-more.focus {
  background-color: #d7dfea;
}
.pointer {
  cursor: pointer;
}

.slide-fade-enter-active {
  transition: all .1s ease;
}
.slide-fade-leave-active {
  /* transition: all .8s cubic-bezier(1.0, 0.5, 0.8, 1.0); */
}
.slide-fade-enter, .slide-fade-leave-to
/* .slide-fade-leave-active for below version 2.1.8 */ {
  transform: translateY(200px);
  opacity: 0;
  /* overflow-y: hidden; */
}
</style>

