<template>
    <div>
      <el-button type="primary" style="margin-bottom: 15px" @click="publish()"><i class="el-icon-plus"></i>发布</el-button>
      <AddTutor v-if="dialogVisible" v-on:child="listenTo" :dialogVisible="dialogVisible" :major="major" :myrow="myrow" :edit="edit"></AddTutor>
      <el-table  :data="tableData" style="width: 100%">

        <el-table-column type="expand">
          <template slot-scope="props">
            <div v-for="(item, index) in props.row.arrange" :key="index" style="margin-bottom: 20px">
              <span>{{item.tutor.tutorName}}</span>
              <span v-if="item.arranst.length > 0">
                ：
                <span v-for="item2 in item.arranst" :key="item2.identity" style="margin-left: 15px">
                  {{item2.stuName}}
                  <el-popover placement="right" width="200" trigger="hover">
                    <div v-if="item2.sex == 1">性别：男</div>
                    <div v-if="item2.sex == 2">性别：女</div>
                    <div>学号：{{item2.sno}}</div>
                    <div>手机：{{item2.contactPhone}}</div>
                    <div>邮箱：{{item2.email}}</div>
                    <el-button size="mini" slot="reference" icon="el-icon-view" circle></el-button>
                  </el-popover>
                </span>
              </span>
            </div>
          </template>
        </el-table-column>

        <el-table-column label="发布日期" width="220">
          <template slot-scope="scope">
            <i class="el-icon-time"></i>
            <span style="margin-left: 10px">{{ scope.row.time }}</span>
          </template>
        </el-table-column>
        <el-table-column label="发布企业" width="240">
        <template slot-scope="scope">
          <span>{{ scope.row.comName }}</span>
        </template>
      </el-table-column>
        <el-table-column label="招聘专业" width="240">
          <template slot-scope="scope">
            <span>{{ scope.row.grade }}级{{ scope.row.major }}</span>
          </template>
        </el-table-column>
        <el-table-column label="状态" width="220">
          <template slot-scope="scope">
            <span>{{ scope.row.status }}</span>
          </template>
        </el-table-column>
        <el-table-column label="操作">
          <template slot-scope="scope">
            <el-button type="primary" v-if="scope.row.status != '历史记录'" icon="el-icon-edit" circle @click="handleEdit(scope.$index, scope.row)"></el-button>
            <el-button type="primary" v-if="scope.row.status == '历史记录'" disabled icon="el-icon-edit" circle></el-button>
            <el-button type="danger" icon="el-icon-delete" circle @click="handleDelete(scope.$index, scope.row)"></el-button>
          </template>
        </el-table-column>
      </el-table>
    </div>
</template>

<script>
  import AddTutor from './AddTutor'
  import { common } from '../../../main'
    export default {
      name: 'Tutor',
      components: {
        AddTutor
      },
      data () {
        return {
          power: this.$store.getters.showPower,
          mycom: {},
          activeName: 'first',
          tableData: [],
          newtime: '',
          dialogVisible: false,
          major: [],
          myrow: [],
          edit: 0
        }
      },
      methods: {
        handleClick () {},
        publish () {
          this.edit = 0
          this.dialogVisible = true
        },
        getData () {
          let that = this
          this.$http.post('/practice/recruitment/major').then(function (res) {
            that.major = res.data.data
          })
          this.$http.post('/apprenticeship/tutor/getinfo', {power: this.power}).then(function (res) {
            that.tableData = res.data.data['info']
            that.newtime = res.data.data['time']
            console.log(res.data.data)
          })
        },
        listenTo (visible) {
          if (visible == 1) {
            this.dialogVisible = false
            this.getData()
            common.$emit('change', '信息已修改！')
          } else {
            this.dialogVisible = false
          }
        },
        handleEdit (index, row) {
          this.edit = 1
          this.myrow = row
          this.dialogVisible = true
        }
      },
      mounted () {
        this.getData()
        common.$on('change', (data) => {
         this.getData()
        })
      }
    }
</script>

<style scoped>
  .demo-table-expand {
    font-size: 0;
  }
  .demo-table-expand label {
    width: 90px;
    color: #99a9bf;
  }
  .demo-table-expand .el-form-item {
    margin-right: 0;
    margin-bottom: 0;
    width: 50%;
  }
  .el-table .warning-row {
    background: oldlace;
  }
  .el-table .success-row {
    background: #f0f9eb;
  }
  .amap-demo {
    height: 300px;
  }
</style>
