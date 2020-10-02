<template>
    <div>
      <el-table :data="tableData" style="width: 100%" max-height="250" v-show="comVisible">
        <el-table-column fixed prop="comName" label="企业名称" width="200">
        </el-table-column>
        <el-table-column prop="comPhone" label="联系电话" width="150">
        </el-table-column>
        <el-table-column prop="comEmail" label="邮箱" width="150">
        </el-table-column>
        <el-table-column prop="comAddress" label="企业地址" width="300">
        </el-table-column>
        <el-table-column label="操作" width="120">
          <template slot-scope="scope">
            <el-button @click.native.prevent="seeRow(scope.$index, scope.row)" type="text" size="small">查看
            </el-button>
          </template>
        </el-table-column>
      </el-table>
      <el-table :data="tableData2" style="width: 100%" v-if="stVisible">
        <el-table-column type="expand">
          <template slot-scope="props">
            <el-table :data="props.row.student" label-position="left" inline class="demo-table-expand">
              <el-table-column fixed prop="stuName" label="学生" width="200">
              </el-table-column>
              <el-table-column label="日总结" width="120">
                <template slot-scope="scope">
                  <el-button @click.native.prevent="seeDay(scope.$index, scope.row)" type="text" size="small">查看
                  </el-button>
                </template>
              </el-table-column>
              <el-table-column label="周总结" width="120">
                <template slot-scope="scope">
                  <el-button @click.native.prevent="seeWeek(scope.$index, scope.row)" type="text" size="small">查看
                  </el-button>
                </template>
              </el-table-column>
              <el-table-column label="月总结" width="120">
                <template slot-scope="scope">
                  <el-button @click.native.prevent="seeMonth(scope.$index, scope.row)" type="text" size="small">查看
                  </el-button>
                </template>
              </el-table-column>
            </el-table>
          </template>
        </el-table-column>
        <el-table-column label="企业" width="180">
          <template slot-scope="scope">
            <span style="margin-left: 10px">{{ scope.row.recruit.company }}</span>
          </template>
        </el-table-column>
        <el-table-column label="标题" width="180">
          <template slot-scope="scope">
            <span style="margin-left: 10px">{{ scope.row.recruit.subject }}</span>
          </template>
        </el-table-column>
        <el-table-column label="实习开始时间" width="180">
          <template slot-scope="scope">
            <span style="margin-left: 10px">{{ scope.row.recruit.recruitStart }}</span>
          </template>
        </el-table-column>
        <el-table-column label="实习结束时间" width="180">
          <template slot-scope="scope">
            <span style="margin-left: 10px">{{ scope.row.recruit.recruitEnd }}</span>
          </template>
        </el-table-column>
        <el-table-column label="地址" width="180">
          <template slot-scope="scope">
            <span style="margin-left: 10px">{{ scope.row.recruit.address }}</span>
          </template>
        </el-table-column>
        <el-table-column label="专业" width="180">
          <template slot-scope="scope">
            <span style="margin-left: 10px">{{ scope.row.recruit.major }}</span>
          </template>
        </el-table-column>
        <el-table-column label="负责教师" width="180">
          <template slot-scope="scope">
            <span style="margin-left: 10px">{{ scope.row.recruit.teacher }}</span>
          </template>
        </el-table-column>
      </el-table>
    </div>
</template>

<script>
    export default {
      name: 'Summary',
      data () {
        return {
          tableData: [],
          tableData2: [],
          comVisible: true,
          stVisible: false
        }
      },
      methods: {
        seeRow (index, row) {
          this.comVisible = false
          this.stVisible = true
          let that = this
          this.$http.post('/gengangpractice/summary/getrecuitst', { id: row.id }).then(function (res) {
            that.tableData2 = res.data.data
          })
        }
      },
      mounted () {
        let that = this
        this.$http.post('/gengangpractice/summary/getcom').then(function (res) {
          that.tableData = res.data.data
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
</style>
