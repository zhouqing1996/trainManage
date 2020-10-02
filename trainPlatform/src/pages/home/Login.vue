<template>
  <div class="page">
    <div class="loginwarrp">
      <div class="logo">校企合作平台</div>
      <div class="login_form">
          <li class="login-item">
            <span>用户名：</span>
            <input type="text" id="NAME" name="NAME" class="login_input" v-model="name">
            <span class="error"></span>
          </li>
          <li class="login-item">
            <span>密　码：</span>
            <input type="password" id="password" name="password" class="login_input" v-model="password">
            <span class="error"></span>
          </li>
          <div class="clearfix"></div>
          <li class="login-sub">
            <input type="button"  value="登录" v-on:click="getLogin"/>
          </li>
      </div>
    </div>
  </div>
</template>

<script>
  import '../../common/js/login.js'

  export default {
    name: 'Login',
    data () {
      return {
        name: '',
        password: ''
      }
    },
    methods: {
      getLogin () {
        this.$http.post('/index/login', { username: this.name, password: this.password })
          .then((res) => {
            if (res.data.data != null) {
              if (res.data.data.kind == 9) {
                alert('登入失败，请确认权限')
                return
              }
              this.$store.dispatch('login', res.data.data)
              alert(res.data.message)
              this.$store.dispatch('showByLogin')
              this.$router.push('/home/index')
              this.flushCom()
            } else {
              alert(res.data.message)
            }
          }, (err) => {
            console.log(111, err)
          })
      },
        flushCom: function () {
            this.$router.go(0)
        }
    }
  }

</script>

<style scoped>
  @import "../../common/css/login.css";
</style>
