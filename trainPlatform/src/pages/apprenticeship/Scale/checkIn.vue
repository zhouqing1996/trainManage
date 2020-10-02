<template>
    <div >
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
    </div>
</template>

<script>
    export default {
      name: 'checkIn',
      data () {
        return {
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
          this.$http.post('/practice/clock/clocklist', { date: this.form.date, sno: this.form.sno, class: this.class }).then(function (res) {
            that.$emit('child', res.data.data)
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
        this.$http.post('/practice/clock/getclass').then(function (res) {
          that.form.majorclass = res.data.data
          console.log(res.data.data)
        })
      }
    }
</script>

<style scoped>

</style>
