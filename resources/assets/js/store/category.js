import api from '../api';

export default {
  state: {
    // All category.
    categorys: [],
  },
  getters: {
    getCategoryByAlias: (state) => (alias) => {
      return state.categorys.find(category => category.alias === alias);
    }
  },
  mutations: {
    setCategory(state) {
      api.getCategory(localStorage.user).then(function (res) {
        state.categorys = res.body.data;
      });
    },
  }
};