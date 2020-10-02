<template>
    <div>
      <el-dialog title="发布招聘" :visible.sync="dvisible" @close='closeDialog'>
        <el-form :model="form">
          <el-form-item label="标题" :label-width="formLabelWidth">
            <el-input v-model="form.subject"></el-input>
          </el-form-item>
          <el-form-item label="内容" :label-width="formLabelWidth">
            <el-input type="textarea" v-model="form.content"></el-input>
          </el-form-item>
          <el-form-item label="发布企业" :label-width="formLabelWidth">
            <el-select v-if="power['userkind'] == '1'" v-model="mycompany" placeholder="请选择">
              <el-option v-for="item in company" :key="item.id" :label="item.comName" :value="item.id">
              </el-option>
            </el-select>
            <span v-if="power['userkind'] == '2'">{{company['comName']}}</span>
          </el-form-item>
          <el-form-item label="招聘年级" :label-width="formLabelWidth">
            <el-select v-model="mygrade" placeholder="请选择">
              <el-option v-for="item in allgrade" :mygrade="item" :key="item" :label="item" :value="item">
              </el-option>
            </el-select>
          </el-form-item>
          <el-form-item label="招聘专业" :label-width="formLabelWidth">
            <el-select v-model="mymajor" placeholder="请选择">
              <el-option v-for="item in allmajor" :mymajor="item.id" :key="item.id" :label="item.major" :value="item.id">
              </el-option>
            </el-select>
            <el-input-number v-model="form.num" :min="1" :max="50" label="招聘人数"></el-input-number>人
          </el-form-item>
          <el-form-item label="开始时间 — 结束时间" :label-width="formLabelWidth">
            <el-date-picker v-model="form.date" value-format="yyyy-MM-dd" type="daterange" range-separator="至" start-placeholder="开始日期" end-placeholder="结束日期">
            </el-date-picker>
          </el-form-item>
        </el-form>
        <div slot="footer" class="dialog-footer">
          <el-button @click="visible(0)">取 消</el-button>
          <el-button type="primary" @click="visible(1)">确 定</el-button>
        </div>
      </el-dialog>
    </div>
</template>

<script>
    export default {
      name: 'AddRecruit',
      props: ['dialogVisible', 'major', 'grade'],
      data () {
        return {
          power: this.$store.getters.showPower,
          form: {
              // grade:'',//学生年级
            subject: '',
            content: '',
            date: '',
            num: 5
          },
          formLabelWidth: '150px',
          dvisible: false,
          allmajor: [],
          mymajor: '',
          company: [],
          mycompany: '',
            allgrade: [],
            mygrade: ''
        }
      },
      methods: {
        visible (param) {
          if (param == 1) {
            let that = this
            let com = ''
            if (this.power['userkind'] == '2') {
              com = this.company['id']
            } else {
              com = this.mycompany
            }
            this.$http.post('/probation/recruit/addrecruit', { form: this.form, major: this.mymajor, grade: this.mygrade, company: com }).then(function (res) {
                that.$emit('child', 1)
            })
            console.log(this.form, this.mymajor, this.mygrade, this.mycompany)
          }
        },
        closeDialog () {
          this.$emit('child', 0)
        }
      },
      mounted () {
        let that = this
        this.$http.post('/probation/recruit/company', {power: this.power}).then(function (res) {
          that.company = res.data.data
          console.log(res.data.data)
        })
        if (this.dialogVisible) {
          this.dvisible = this.dialogVisible
          this.allmajor = this.major
            this.allgrade = this.grade
        }
      }
    }
</script>

<style scoped>

</style>
