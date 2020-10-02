// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import Vuerouter from 'vue-router'
import Layout from './components/Layout.vue'
// import VueResource  from 'vue-resource'
import routes from './router/index'
import store from './store/store'

import ElementUI from 'element-ui'
import 'element-ui/lib/theme-chalk/index.css'
// VUE-Beauty
// import 'vue-beauty/package/style/vue-beauty.min.css'
// import vueBeauty from 'vue-beauty'
import filters from './filters'

import axios from 'axios'
import VueAxios from 'Vue-axios'
// 引入图标库
// 下载
// import file-saver from 'file-saver'
// Vue.use(file-saver)
//引入图表库
import echarts from 'echarts';
Vue.prototype.$echarts = echarts
//图标
import VueIconFont from 'vue-icon-font'
import './common/css/fonts/iconfont.css'
import './common/css/fonts/iconfont.js'
//富文本编辑器
import VueQuillEditor from 'vue-quill-editor'
import 'quill/dist/quill.core.css'
import 'quill/dist/quill.snow.css'
import 'quill/dist/quill.bubble.css'


import 'bootstrap/dist/js/bootstrap.min.js'

// 上传
import uploader from 'vue-simple-uploader'
Vue.use(ElementUI)
Vue.prototype.$echarts = echarts
Vue.use(VueQuillEditor)
//引入excel下载所需文件
// import Bolb from "./excel/Bolb.js"
// import "@/excel/Bolb.js"
// import Export2Excel from "@/excel/Export2Excel.js"

Vue.use(ElementUI)
// Vue.use(vueBeauty)
//过滤器
Object.keys(filters).forEach(key => Vue.filter(key, filters[key]))
// import $ from 'jquery'

import 'bootstrap/dist/js/bootstrap.min.js'

Vue.use(VueIconFont)
Vue.use(VueAxios,axios)//注册
// Vue.use(VueResource)
Vue.use(Vuerouter)

//引用上传
// import uploader from 'vue-simple-uploader'
Vue.use(uploader)

//引用远程和本地的打包访问路由配置
import baseConfig from 'config';



import AMap from 'vue-amap'
Vue.use(AMap)
AMap.initAMapApiLoader({
  key: 'fb5e560958c8fc6733887e42cecb5ffa',
  plugin: ['AMap.Geolocation', 'AMap.Geocoder']
})

//滚动
/*var vueSmoothScroll = require('vue-smoothscroll');
Vue.use(vueSmoothScroll);*/

axios.interceptors.request.use(function (config) {  //配置发送请求的信息,添加access_token信息
  if(config.url.indexOf("/translate")<0)
    config.url=baseConfig.apiBaseUrl+config.url;

  store.dispatch('showLoading')
  const temp_token=JSON.parse(localStorage.getItem("vuex"))?JSON.parse(localStorage.getItem("vuex"))['access_token']:""
  if (temp_token!="") {
    config.url +="?access_token="+temp_token ;
  }
  return config
}, function (error) {
  return Promise.reject(error);
});
axios.interceptors.response.use(function (response) { //配置请求回来的信息
  store.dispatch('hideLoading')
  return response;
}, function (error) {
  return Promise.reject(error);
});




// Vue.prototype.$http=axios;

const router=new Vuerouter({
  mode:'history',
  scrollBehavior: () => ({ y: 0 }),
  base:"/trainManage/trainPlatform",
  routes,
  linkActiveClass:'active'
})

export var common = new Vue()
export var common2 = new Vue()
export var common3 = new Vue()
export var common4 = new Vue()
export var common5 = new Vue()
export var common6 = new Vue()
export var common7 = new Vue()
export var common8 = new Vue()
export var common9 = new Vue()
export var common10 = new Vue()

export var commonFinance = new Vue()
export var commonAttend = new Vue()
export var commonProfessor = new Vue()
export var commonReport = new Vue()
export var commonMember = new Vue()
export var commonExpert =new Vue();

Vue.config.productionTip = false

/* eslint-disable no-new */
new Vue({
  el: '#app',
  router,
  store,
  template: '<Layout/>',
  components: { Layout }
})
