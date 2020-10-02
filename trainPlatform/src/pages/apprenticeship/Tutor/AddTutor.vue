<template>
    <div>
      <el-dialog title="发布" :visible.sync="dvisible" @close='closeDialog'>
        <el-form>
          <el-form-item label="发布企业" :label-width="formLabelWidth">
            <el-select v-if="power['userkind'] == '1'" v-model="mycompany" placeholder="请选择">
              <el-option v-for="item in company" :mycompany="item.id" :key="item.id" :label="item.comName" :value="item.id">
              </el-option>
            </el-select>
            <span v-if="power['userkind'] == '2'">{{company['comName']}}</span>
          </el-form-item>
          <el-form-item label="招聘专业" :label-width="formLabelWidth">
            <el-select v-model="mymajor" placeholder="请选择">
              <el-option v-for="item in allmajor" :mymajor="item.id" :key="item.id" :label="item.major" :value="item.id">
              </el-option>
            </el-select>
          </el-form-item>
          <el-form-item label="招聘年级" :label-width="formLabelWidth">
            <el-select v-model="mygrade" placeholder="请选择">
              <el-option v-for="item in grade" :mygrade="item" :key="item" :label="item" :value="item">
              </el-option>
            </el-select>
          </el-form-item>
          <el-form-item label="选择导师" :label-width="formLabelWidth">
            <div></div>
            <el-select v-model="mytutor" multiple placeholder="请选择">
              <el-option v-for="item in tutor" v-if="mycompany==item.id"  :key="item.identity" :label="item.tutorName" :value="item.identity">
              </el-option>
            </el-select>
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
      props: ['dialogVisible', 'major', 'myrow', 'edit'],
      data () {
        return {
          power: this.$store.getters.showPower,
          formLabelWidth: '150px',
          dvisible: false,
          allmajor: [],
          mymajor: '',
          company: [],
          tutor: [],
          mycompany: '',
          mytutor: '',
          mygrade: '',
          grade: []
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
            this.$http.post('/apprenticeship/tutor/addtutor', { major: this.mymajor, company: com, tutor: this.mytutor, edit: this.edit, rowId: this.myrow.id }).then(function (res) {
                that.$emit('child', 1)
            })
            console.log(this.mymajor, this.mycompany, this.myrow.id)
          } else {
            this.$emit('child', 0)
          }
        },
        closeDialog () {
          this.$emit('child', 0)
        }
      },
      mounted () {
        let that = this
        this.$http.post('/practice/recruitment/company', {power: this.power}).then(function (res) {
          that.company = res.data.data
          console.log(res.data.data)
        })
        this.$http.post('/apprenticeship/tutor/gettutor', {power: this.power}).then(function (res) {
          that.tutor = res.data.data['tutor']
          that.grade = res.data.data['grade']
          console.log(res.data.data)
        })
        if (this.dialogVisible) {
          this.dvisible = this.dialogVisible
          this.allmajor = this.major
        }
        if (this.myrow) {
          this.mycompany = this.myrow.comId
          this.mymajor = this.myrow.majorId
          this.mygrade = this.myrow.grade
        }
      }
    }
</script>

<style scoped>

</style>
