<template>
  <div class="style1">
    <el-tabs v-model="activeName" type="card" @tab-click="handleClick">
      <el-tab-pane label="安排" name="first"><Arrange></Arrange></el-tab-pane>
      <el-tab-pane label="实习" name="fifth"><Evaluate v-if="hackReset"></Evaluate></el-tab-pane>
    </el-tabs>
  </div>
</template>

<script>
  import Arrange from './Tutor/Arrange'
  import Evaluate from './Evaluate/Evaluate'
    export default {
      name: 'Teacher',
      components: {
        Arrange,
        Evaluate
      },
      data () {
        return {
          activeName: 'first',
          hackReset: true,
          clockReset: true
        }
      },
      methods: {
        handleClick (tab, event) {
          if (tab.name == 'fifth') {
            this.hackReset = false
            this.$nextTick(() => {
              this.hackReset = true
            })
          } else if (tab.name == 'forth') {
            this.clockReset = false
            this.$nextTick(() => {
              this.clockReset = true
            })
          }
        }
      },
      created () {
        this.table = this.$route.query.table;
        console.log(this.table)
        if (this.table == 1){
          this.activeName = 'second';
        }
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
