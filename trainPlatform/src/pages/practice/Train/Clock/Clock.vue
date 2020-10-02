<template>
  <div style="width: 100%;height: 100%">
    <el-form :model="form">
      <el-form-item label="时间段选择" :label-width="formLabelWidth">
        <el-date-picker v-model="form.date" value-format="yyyy-MM-dd" type="daterange" range-separator="至" start-placeholder="开始日期" end-placeholder="结束日期">
        </el-date-picker>
      </el-form-item>
      <el-form-item label="专业" :label-width="formLabelWidth">
        <span>{{form.majorclass.major}}</span>
      </el-form-item>
      <el-form-item label="学号" :label-width="formLabelWidth">
        <el-select v-model="form.sno" placeholder="请选择">
          <el-option v-for="item in stu" :key="item.value" :label="item.label" :value="item.value">
          </el-option>
        </el-select>
      </el-form-item>
    </el-form>
    <el-button type="primary" size="mini" icon="el-icon-search" v-on:click="search" style="margin-left: 20px">查找</el-button>
    <el-button type="primary" size="mini" icon="el-icon-delete" v-on:click="reset">清空</el-button>
    <CheckInChart :xdata="xdata" :ydata="ydata"></CheckInChart>
  </div>
</template>

<script>
  import CheckInChart from './checkInChart'
  export default {
    name: 'Clock',
    props: ['row'],
    components: {CheckInChart},
    data () {
      return {
        power: this.$store.getters.showPower,
        xdata: [],
        ydata: [],
        form: {
          date: '',
          majorclass: [],
          sno: ''
        },
        stu: [],
        class: [],
        formLabelWidth: '100px'
      }
    },
    methods: {
      // 查找
      search () {
        console.log(this.form)
        let that = this
        this.$http.post('/practice/clock/clocklist2', { date: this.form.date, sno: this.form.sno, rowId: this.row.id }).then(function (res) {
          that.xdata = res.data.data[0]
          that.ydata = res.data.data[1]
          console.log(res.data.data)
        })
      },
      // 清空
      reset () {
        this.form.date = ''
        this.form.sno = ''
      },
      handleChange (value) {
        this.class = value
        console.log(value)
      }
      // listenMap (data) {
      //   console.log(data)
      //   if (data) {
      //     this.xdata = data[0]
      //     this.ydata = data[1]
      //   }
      // }
    },
    mounted () {
      console.log(this.row)
      let that = this
      this.$http.post('/practice/clock/getclass', {power: this.power, recruitId: this.row.id}).then(function (res) {
        that.form.majorclass = res.data.data.major
        that.stu = res.data.data.student
        console.log(res.data.data)
      })
      this.search()
    }
  }
</script>

<style scoped>

</style>
