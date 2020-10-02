<template>
  <div class="style1">
    <el-tabs v-model="activeName" type="card" @tab-click="handleClick">
      <el-tab-pane label="招聘信息" name="first"><RecruitTeacher></RecruitTeacher></el-tab-pane>
      <el-tab-pane label="学生实习" name="fifth"><TrainTeacher v-if="hackReset&&flag==1"></TrainTeacher>
        <SummaryTeacher1 :student="student" :mark="mark" v-if="flag == 2"></SummaryTeacher1></el-tab-pane>
      <el-tab-pane label="评价量表" name="second"><ScaleTeacher></ScaleTeacher></el-tab-pane>
      <!--<el-tab-pane label="实习总结" name="third"><SummaryTeacher></SummaryTeacher></el-tab-pane>-->
      <el-tab-pane label="签到统计" name="forth"><Clock v-if="clockReset"></Clock></el-tab-pane>
    </el-tabs>
  </div>
</template>

<script>
  import { common7,common9} from '../../main'
  import RecruitTeacher from './Recruitment/RecruitTeacher'
  import TrainTeacher from './Train/TrainTeacher'
  import ScaleTeacher from './Scale/ScaleTeacher'
  import Clock from './Scale/Clock'
  import SummaryTeacher from './Summary/SummaryTeacher'
  import SummaryTeacher1 from './Summary/SummaryTeacher1'
    export default {
      name: 'Teacher',
      components: {
        RecruitTeacher,
        TrainTeacher,
        ScaleTeacher,
        Clock,
        SummaryTeacher,
        SummaryTeacher1
      },
      data () {
        return {
          activeName: 'first',
          hackReset: true,
          clockReset: true,
          student:[],
          flag:1,
          mark:'teacher'
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
        },
      },
      created () {
        this.table = this.$route.query.table;
        console.log(this.table)
        if (this.table == 1){
          this.activeName = 'second';
        }
        common7.$on('change', (data) => {
          this.flag = 2;
          this.student = data
          console.log(data)
        })
        common9.$on('change', (data) => {
          this.flag = 1;
        })
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
