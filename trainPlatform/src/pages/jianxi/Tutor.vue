<template>
  <div class="style1">
    <el-tabs v-model="activeName" type="card" @tab-click="handleClick">
      <el-tab-pane label="招聘信息" name="first"><RecruitTutor></RecruitTutor></el-tab-pane>
      <el-tab-pane label="学生见习" name="fifth"><TrainTutor v-if="hackReset&&flag==1"></TrainTutor>
        <NewsummaryTeacher1 :student="student" :mark="mark" v-if="flag == 2"></NewsummaryTeacher1></el-tab-pane>
      <el-tab-pane label="见习总结" name="third"><NewsummaryTeacher></NewsummaryTeacher></el-tab-pane>
    </el-tabs>
  </div>
</template>

<script>
    import { common7, common9} from '../../main'
    import RecruitTutor from './Newrecruit/RecruitTutor'
    import TrainTutor from './Newtrain/TrainTutor'
    // import ScaleTeacher from './Scale/ScaleTeacher'
    // import Clock from './Scale/Clock'
    import NewsummaryTeacher from './Newsummary/NewsummaryTeacher'
    import NewsummaryTeacher1 from './Newsummary/NewsummaryTeacher1'
    export default {
        name: 'Tutor',
        components: {
            NewsummaryTeacher1,
            NewsummaryTeacher,
            RecruitTutor,
            TrainTutor
            // ScaleTeacher,
            // Clock,
        },
        data () {
            return {
                activeName: 'first',
                hackReset: true,
                clockReset: true,
                student: [],
                flag: 1,
                mark: 'teacher'
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
            this.table = this.$route.query.table
            console.log(this.table)
            if (this.table == 1) {
                this.activeName = 'second'
            }
            common7.$on('change', (data) => {
                this.flag = 2
                this.student = data
                console.log(data)
            })
            common9.$on('change', (data) => {
                this.flag = 1
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
