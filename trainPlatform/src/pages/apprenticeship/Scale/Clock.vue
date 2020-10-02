<template>
    <div style="width: 100%;height: 100%">
      <el-form :model="form">
        <el-form-item label="时间段选择" :label-width="formLabelWidth">
          <el-date-picker v-model="form.date" value-format="yyyy-MM-dd" type="daterange" range-separator="至" start-placeholder="开始日期" end-placeholder="结束日期">
          </el-date-picker>
        </el-form-item>
        <el-form-item label="专业班级" :label-width="formLabelWidth">
          <el-cascader :options="form.majorclass" change-on-select @change="handleChange"></el-cascader>
        </el-form-item>
        <el-form-item label="学号" :label-width="formLabelWidth">
          <el-input v-model="form.sno" style="width: 210px"></el-input>
        </el-form-item>
      </el-form>
      <el-button type="primary" icon="el-icon-search" v-on:click="search" style="margin-left: 20px">查找</el-button>
      <el-button type="primary" icon="el-icon-delete" v-on:click="reset">清空</el-button>
      <CheckInChart :xdata="xdata" :ydata="ydata"></CheckInChart>
    </div>
</template>

<script>
  import CheckInChart from './checkInChart'
  export default {
    name: 'Clock',
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
        class: [],
        formLabelWidth: '100px'
      }
    },
    methods: {
      // 查找
      search () {
        console.log(this.form)
        let that = this
        this.$http.post('/practice/clock/clocklist', { date: this.form.date, sno: this.form.sno, class: this.class, power: this.power, user: this.$store.state.mutations.user }).then(function (res) {
          // that.$emit('child', res.data.data)
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
    },
    mounted () {
      let that = this
      this.$http.post('/practice/clock/getclass', {power: this.power, user: this.$store.state.mutations.user}).then(function (res) {
        that.form.majorclass = res.data.data
        console.log(res.data.data)
      })
      this.search()
    }
  }
</script>

<style scoped>

</style>
