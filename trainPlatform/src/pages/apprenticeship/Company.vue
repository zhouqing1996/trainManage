<template>
  <div class="style1">
    <el-tabs v-model="activeName" type="card" @tab-click="handleClick">
      <el-tab-pane label="发布" name="first"><Tutor></Tutor></el-tab-pane>
      <el-tab-pane label="评价" name="second"><Evaluate v-if="hackReset"></Evaluate></el-tab-pane>
      <!--<el-tab-pane label="评价量表" name="second"><Scale></Scale></el-tab-pane>-->
    </el-tabs>
  </div>
</template>

<script>
  import Tutor from './Tutor/Tutor'
  import Evaluate from './Evaluate/Evaluate'

  export default {
    name: 'Company',
    components: {
      Tutor,
      Evaluate
    },
    data () {
      return {
        activeName: 'first',
        hackReset: true
      }
    },
    methods: {
      handleClick (tab, event) {
        if (tab.name == 'second') {
          this.hackReset = false
          this.$nextTick(() => {
            this.hackReset = true
          })
        }
        console.log(tab.name)
      }
    },
    created () {
      console.log(this.$store.getters.showPower)
    }
  }
</script>

<style scoped>
  .style1{
    margin-top: 10px;
    margin-left: 10px;
  }
</style>
