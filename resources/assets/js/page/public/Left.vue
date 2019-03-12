<template>
  <div class="col-lg-2 d-none d-xl-block content-left">
    <button type="button" class="btn def-bg-color text-white btn-block btn-sm">{{$t('start_a_discussion')}}</button>

    <p class="left-p margin-25" :class="{'active':switchActive=='all'}" @click="switchCategory('all')"><i class="icon far fa-comments"></i> {{$t('all_discussions')}}</p>
    <!-- <p class="left-p"><i class="icon fas fa-th-large"></i> {{$t('tags')}}</p> -->

    <p class="border-bottom mb-25"></p>

    <p
      v-for="(category,inx) in categorys"
      class="left-p"
      :class="{'active':switchActive==category.alias}"
      @click="switchCategory(category.alias)">
      <i class="icon rounded fas fa-square" :style="'color:' + category.color"></i> {{category.category}}
    </p>
  </div>
</template>

<script>
import store from "../../store/index";
import { mapState, mapActions } from "vuex";

export default {
  name: 'appLeft',
  data() {
    return {
      switchActive: 'all',
    };
  },
  computed: {
    categorys() {
      if (store.state.category.categorys.length == 0) {
        store.commit('setCategory');
      }
      
      return store.state.category.categorys;
    }
  },
  methods: {
    ...mapActions(["getArticle", "refreshArticle"]),
    switchCategory(type) {
      this.switchActive = type;
      store.state.article.searchKey.page = 0;
      store.state.article.searchKey.c = type == 'all' ? '' : type;
      this.refreshArticle();

      if (this.switchActive == 'all') {
        this.$router.push({name: 'home'});
      } else {
        this.$router.push({name: 'home', query: {c: this.switchActive}});
      }
    }
  },
  created() {
    if (typeof this.$route.query.c == 'undefined') {
      var type = 'all';
    } else {
      var type = this.$route.query.c;
    }

    this.switchActive = type;
    store.state.article.searchKey.c = type;
  }
};
</script>

<style scoped>
.left-p:hover {
  color: #4d698e;
  font-weight: bold;
}
.mb-25 {
  margin-bottom: 25px;
}
</style>
