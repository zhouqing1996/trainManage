<template>
  <div style=" margin-top:20px;">
    <div class="meauPanel">
      <router-link to="">
        <div @click="create('custom')">
          <span><i class="iconzidingyi" style="font-size: 70px"></i>
            自定义创建</span>
        </div>
      </router-link>
    </div>
    <div class="meauPanel">
      <router-link to="">
        <div @click="create('template')">
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
    name: 'Surveyindex',
    props: ['flag'],
    data () {
      return {
      }
    },
    methods: {
      create (param) {
        this.$emit('listenToCreate', param)
      }
    },
    created () {
    }
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
