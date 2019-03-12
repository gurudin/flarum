import Vue from 'vue';
import conf from "./conf.json";
import VueResource from 'vue-resource';
import moment from "moment";
Vue.use(VueResource);

Vue.http.interceptors.push(function (request, next) {
  request.headers.set('App-Id', conf.appId);
  if (typeof window.localStorage.user != 'undefined') {
    if (JSON.parse(window.localStorage.user).expiration_time < moment().format('YYYY-MM-DD HH:mm:ii')) {
      window.localStorage.removeItem('user');
    } else {
      request.headers.set('User-Token', JSON.parse(window.localStorage.user).token);
    }
  }
});

export default {
  /** Category */
  getCategory(user) {
    let result = Vue.resource(conf.base + '/category').get();
    
    return result;
  },

  /** Posts */
  getArticle(params) {
    return Vue.resource(conf.base + '/posts').get(params);
  },
  getPostById(id, callback) {
    Vue.resource(conf.base + '/posts/' + id).get().then((res) => {
      return res.data;
    }).then((data) => {
      callback(data);
    });
  },
  // isRead(params, callback) {
  //   Vue.resource(conf.base + '/isRead').get(params).then((res) => {
  //     return res.data;
  //   }).then((data) => {
  //     callback(data);
  //   });
  // },

  /** Login or regisger */
  regisger(params, callback) {
    Vue.resource(conf.base + '/register').save(params).then((res) => {
      return res.data;
    }).then((data) => {
      callback(data);
    });
  },
  login(params, callback) {
    Vue.resource(conf.base + '/login').save(params).then((res) => {
      return res.data;
    }).then((data) => {
      callback(data);
    });
  },
  logout(params, callback) {
    Vue.resource(conf.base + '/logout').save(params).then((res) => {
      return res.data;
    }).then((data) => {
      callback(data);
    });
  },
};