require('./bootstrap');

window.Vue = require('vue');

import VueRouter from 'vue-router';
import LazyYoutube from "vue-lazytube";
import routes from './routes';
import VueAWN from "vue-awesome-notifications"
import VueMask from 'v-mask';
import vueVimeoPlayer from 'vue-vimeo-player';
import excel from 'vue-excel-export'
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
import VueGtag from "vue-gtag";
// import vue-panzoom
import panZoom from 'vue-panzoom'
import { Plugins } from "@capacitor/core";

import VueQrcodeReader from "vue-qrcode-reader";

Vue.use(VueQrcodeReader);

import VuePwaInstallPlugin from "vue-pwa-install";
 
Vue.use(VuePwaInstallPlugin);

/* import the fontawesome core */
import { library } from '@fortawesome/fontawesome-svg-core'

/* import font awesome icon component */
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

/* import specific icons */
import { faUserSecret } from '@fortawesome/free-solid-svg-icons'

Vue.component(
    'menu-component',
    require('./components/Menu.vue').default
);

Vue.component(
    'poll-component',
    require('./components/PollComponent.vue').default
);

/* add icons to the library */
library.add(faUserSecret)

/* add font awesome icon component */
Vue.component('font-awesome-icon', FontAwesomeIcon)

Vue.component("LazyYoutube", LazyYoutube);
Vue.use(panZoom);
Vue.use(BootstrapVue)
Vue.use(IconsPlugin)
Vue.use(excel)
Vue.use(vueVimeoPlayer)
Vue.use(VueAWN);
Vue.use(VueRouter);
Vue.use(VueMask);
Vue.prototype.$app = Plugins.App; // Agregamos el plugin App a Vue

const gtagId = '';

Vue.use(VueGtag, {
    config: { id: gtagId }
  });

const app = new Vue({
    el: '#app',
    router: new VueRouter(routes)
});
