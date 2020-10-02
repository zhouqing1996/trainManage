<template>
  <div>
    <EditArrange v-if="!visible" v-on:child="listenTo" :myrow="myrow"></EditArrange>
    <el-table v-if="visible" :data="tableData" style="width: 100%">
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
  import EditArrange from './EditArrange'
  import { common } from '../../../main'
  export default {
    name: 'Arrange',
    components: {
      EditArrange
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
        disabled: false,
        visible: true,
        myrow: ''
      }
    },
    methods: {
      handleEdit (index, row) {
        this.visible = false
        this.myrow = row
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
          this.getData()
          this.visible = true
          common.$emit('change', '信息已修改！')
        } else {
          this.visible = true
        }
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
