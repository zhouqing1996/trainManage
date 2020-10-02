<template>
  <div style=" margin-top:20px;">
    <div class="meauPanel">
      <router-link :to="{ path:'/requirement/surveyedit',query:{id: 0,flag:flag}}">
        <div>
          <span><i class="iconzidingyi" style="font-size: 70px"></i>
            自定义创建</span>
        </div>
      </router-link>
    </div>
    <div class="meauPanel">
      <router-link v-if="flag==20" :to="{ path:'/practice/Scale/scaletemplate',query:{id: 0,flag:flag}}">
        <div >
          <span>
              <i class="iconchuangjiandingdan" style="font-size: 60px"></i>
            模板创建
          </span>
        </div>
      </router-link>
      <router-link v-else :to="{ path:'/requirement/templatelist',query:{id: 0,flag:flag}}">
        <div >
          <span>
              <i class="iconchuangjiandingdan" style="font-size: 60px"></i>
            模板创建
          </span>
        </div>
      </router-link>
    </div>
  </div>
</template>

<script>

  export default {
    name: "Surveyindex",
    data() {
      return {
        flag: ''
      }
    },
    created() {
      this.flag = this.$route.query.flag;
    },
  }
</script>

<style scoped>
  .meauPanel{
    text-align: center;
    line-height: 260px;
    width: 250px;
    height: 230px;
    float: left;
    border: 1px solid #ECECEC;
    background:#FFF;
    /*border:1px solid #fff;*/
    border-radius: 10px;
    font-size: 18px;
    margin: 10px;
  }
</style>
