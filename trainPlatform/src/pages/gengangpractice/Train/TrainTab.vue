<template>
  <div>
    <el-tabs v-model="activeNow">
      <el-tab-pane label="签到统计" name="first"><Clock v-if="clockvisible" :row="row"></Clock></el-tab-pane>
      <el-tab-pane label="评价量表" name="second"><Scale v-if="scalevisible" :row="row"></Scale></el-tab-pane>
      <el-tab-pane label="实习总结" name="third"><Summary v-if="summaryvisible" :row="row"></Summary></el-tab-pane>
    </el-tabs>
  </div>
</template>

<script>
  import Clock from './Clock/Clock'
  import Scale from './Scale/Scale'
  import Summary from './Summary/Summary'
    export default {
      name: 'TrainTab',
      props: ['row'],
      components: {
        Clock,
        Scale,
        Summary
      },
      data () {
        return {
          activeNow: 'first',
          nowrow: [],
          clockvisible: false,
          summaryvisible: false,
          scalevisible: false
        }
      },
      mounted () {
        this.clockvisible = true
        this.scalevisible = true
        this.summaryvisible = true
        console.log(this.row)
      }
    }
</script>

<style scoped>

</style>
