import Vue from 'vue';
import VueResource from 'vue-resource';
import router from './router';
import VueI18n from 'vue-i18n';
import store from "./store/index";
import "@fortawesome/fontawesome-free";
import Moment from "moment";

Vue.use(VueResource);
Vue.use(VueI18n);

require('./bootstrap');

// I18n
import Languages from "./i18n/lang.js";
const i18n = new VueI18n({
  locale: 'zh-cn',
  messages: Languages
});

/**
 * Global
 */
import Api from "./api";
Vue.prototype.GLOBAL = {
  api: Api,
};

if (document.getElementById("app") != null) {
  // Public func
  Vue.prototype.alert = function (name, text) {
    $("#alert").attr('class', 'alert ' + name);
    $("#alertContent").html(text);
    $("#alert").fadeIn();

    setTimeout(() => {
      $("#alert").fadeToggle();
    }, 2000);
  }

  // Moment
  Vue.prototype.moment = Moment;

  // localStorage
  Vue.prototype.localStorage = window.localStorage;

  new Vue({
    router,
    i18n,
    store
  }).$mount('#app');
}

(function ($) {
  /**
   * Button for loading
   * @param {string} text
   * 
   * @example $(event).loading(); / $(event).loading('reset');
   */
  var loadingDefaultValue = '';
  $.fn.loading = function (text = '') {
    text == '' ? text = 'loading...' : text;
    if (text == 'reset') {
      this.removeClass('disabled').attr('disabled', false).html(loadingDefaultValue);
    } else {
      loadingDefaultValue = this[0].innerHTML;
      this.addClass('disabled').attr('disabled', true).html(text);

      return this;
    }
  };  
})(jQuery);
