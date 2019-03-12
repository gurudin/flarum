import Vue from 'vue'
import Router from 'vue-router'
import Main from "../page/Main.vue";
// Site
import Login from "../page/site/Login.vue";
import Register from "../page/site/Register.vue";

// // Detail
import Detail from "../page/Detail.vue";

Vue.use(Router)

export default new Router({
  // mode: 'history',
  routes: [
    {
      path: '/',
      name: 'home',
      component: Main,
      children: [
        {
          name: 'login',
          path: 'login/:source',
          component: Login
        },
        {
          name: 'register',
          path: 'register/:source',
          component: Register
        }
      ]
    },
    {
      path: '/d/:id',
      name: 'detail',
      component: Detail,
      children: [
        {
          name: 'd-login',
          path: 'login/:source',
          component: Login
        },
        {
          name: 'd-register',
          path: 'register/:source',
          component: Register
        }
      ]
    }
  ]
})
