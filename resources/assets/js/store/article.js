import api from "../api";

export default {
  state: {
    articleItem: [],
    searchKey: {
      order: 'new', // new|top
      page: 1,
      c: '', // Category alias.
      cId: '', // Category id.
      filter: '', // Search key.
    },
  },
  mutations: {
    getArticle(state, result) {
      if (state.searchKey.page > 1) {
        state.articleItem = {
          item: state.articleItem.item.concat(result.data.item),
          page: result.data.page
        };
      } else {
        state.articleItem = result.data;
      }
    },
  },
  actions: {
    getArticle({ state, commit }) {
      if (state.searchKey.c == '') {
        state.searchKey.cId = '';
        api.getArticle(state.searchKey).then(function (res) {
          commit('getArticle', res.body);
        });
      } else {
        setTimeout(() => {
          let category = this.getters.getCategoryByAlias(state.searchKey.c);
          state.searchKey.cId = category.id;

          api.getArticle(state.searchKey).then(function (res) {
            commit('getArticle', res.body);
          });
        }, 100);
      }
    },
    refreshArticle({ dispatch, state }) {
      state.articleItem = [];
      dispatch('getArticle');
    }
  }
};
