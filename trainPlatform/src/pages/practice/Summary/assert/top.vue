<template>
    <div>
      <!--评分问卷选择-->
      <span>评分问卷</span>
      <el-select v-model="questionnaire" filterable placeholder="请选择">
        <el-option
          v-for="item in questionnaireOptions"
          :key="item.value"
          :label="item.label"
          :value="item.value">
        </el-option>
      </el-select>
      <!--评价人员选择-->
      <!--<span>评价人员</span>-->
      <!--<el-select v-model="option" clearable placeholder="请选择">-->
        <!--<el-option-->
          <!--v-for="item in options"-->
          <!--:key="item.value"-->
          <!--:label="item.label"-->
          <!--:value="item.value">-->
        <!--</el-option>-->
      <!--</el-select>-->
      <!--<span v-show="option=='student'" style="margin-left: 20px;">-->
        <!--企业号-->
        <!--<el-input-->
          <!--style="width:20%;"-->
          <!--placeholder="请输入企业号"-->
          <!--v-model="companyId"-->
          <!--clearable>-->
        <!--</el-input>-->
        <!--<el-button type="primary" icon="el-icon-search" v-on:click="search(option,companyId)">查找</el-button>-->
      <!--</span>-->
      <span  style="margin-left: 20px;">
        学号/企业号
        <el-input
          style="width:20%;"
          placeholder="请输入学号/企业号"
          v-model="studentId"
          clearable>
        </el-input>
        <el-button type="primary" icon="el-icon-search" v-on:click="search(questionnaire,studentId)">查找</el-button>
        <el-button type="primary" icon="el-icon-delete" v-on:click="reset">清空</el-button>
      </span>
    </div>
</template>

<script>
    export default {
      name: 'top',
      methods: {
        search (questionnaire, id) {
          console.log(questionnaire, id)
        },
        reset () {
          this.questionnaire = ''
          this.option = ''
          this.studentId = ''
          this.companyId = ''
        }
      },
      data () {
        return {
          options: [{
            value: 'student',
            label: '学生'
          }, {
            value: 'teacher',
            label: '教师'
          }, {
            value: 'company',
            label: '企业'
          }],
          option: '',
          studentId: '',
          companyId: '',
          questionnaireOptions: [{
            value: '001',
            note: 'company',
            label: '问卷1'
          }, {
            value: '002',
            note: 'student',
            label: '问卷2'
          }, {
            value: '003',
            note: 'company',
            label: '问卷3'
          }, {
            value: '004',
            note: 'student',
            label: '问卷4'
          }, {
            value: '005',
            note: 'student',
            label: '问卷5'
          }],
          questionnaire: ''
        }
      }
    }
</script>

<style scoped>

</style>
