<template>
  <div>
    <LoadView v-show="loading"></LoadView>
    <Condition v-show="showByLogin"></Condition>

    <div id="wrapper">
      <Header v-if="showByLogin" v-bind:user-name="getUserName"></Header>

      <Nav v-if="showSider"></Nav>

      <div v-bind:class="{ 'main': showByLogin, 'login_content': !showByLogin }">
        <keep-alive>
          <router-view></router-view>
        </keep-alive>
      </div>

      <div class="clearfix"></div>

      <Fooster v-if="showByLogin"></Fooster>
    </div>
  </div>
</template>

<script>
  import LoadView from './Loading'
  import Fooster from './Fooster'
  import Condition from './Condition'
  import Nav from './Nav'
  import Header from './Header'
  import { mapGetters } from 'vuex'

  import '../utils/jquery-slimscroll/jquery.slimscroll'
  import '../common/js/klorofil-common'

  export default {
    name: 'Layout',

    computed: {
      //映射
      ...mapGetters([
        'getUserName',
        'showByLogin',
        'showSider',   //映射 this.showSider 为 store.getters.showSider
        'loading'   //映射 this.loading 为 store.getters.loading
      ])
    },
    components:{
      LoadView,
      Header,
      Nav,
      Condition,
      Fooster
    },
  }
</script>

<style>
  @import "../common/css/common.css";
  @import "../common/css/reset.css";
  @import '../utils/bootstrap/css/bootstrap.min.css';
  @import '../utils/font-awesome/css/font-awesome.css';
  @import '../utils/linearicons/style.css';
  @import "../common/css/main.css";

  .login_content{
    width: 100%;
  }
  /*#wrapper{*/
    /*position: relative;*/
  /*}*/



</style>
