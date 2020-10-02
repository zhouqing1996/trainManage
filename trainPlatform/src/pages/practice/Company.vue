<template>
  <div class="style1">
    <el-tabs v-model="activeName" type="card" @tab-click="handleClick">
      <el-tab-pane label="招聘信息" name="first"><Recruitment></Recruitment></el-tab-pane>
      <el-tab-pane label="学生实习" name="second">
        <Train v-if="hackReset&&flag==1"></Train>
        <SummaryTeacher1 :student="student" :mark="mark" v-if="flag == 2"></SummaryTeacher1>
      </el-tab-pane>
      <!--<el-tab-pane label="评价量表" name="second"><Scale></Scale></el-tab-pane>-->
    </el-tabs>
  </div>
</template>

<script>
  import { common8,common10 } from '../../main'
  import Recruitment from './Recruitment/Recruitment'
  import Train from './Train/Train'
  import Clock from './Scale/Clock'
  import SummaryTeacher1 from './Summary/SummaryTeacher1'

  export default {
    name: 'Company',
    components: {
      Recruitment,
      Clock,
      Train,
      SummaryTeacher1
    },
    data () {
      return {
        activeName: 'first',
        hackReset: true,
        flag:1,
        student:[],
        mark:'company'
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
      },
    },
    created () {
      // common8.$on('change', (data) => {
      //   this.flag = 2;
      //   console.log(data)
      // })
      common8.$on('change', (data) => {
        this.flag = 2;
        this.student = data
        console.log(data)
      })
      common10.$on('change', (data) => {
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
