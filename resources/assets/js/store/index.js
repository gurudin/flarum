import Vue from 'vue';
import Vuex from 'vuex';
import category from "./category";
import article from "./article";

Vue.use(Vuex);

export default new Vuex.Store({
  modules: {
    category,
    article,
  }
});