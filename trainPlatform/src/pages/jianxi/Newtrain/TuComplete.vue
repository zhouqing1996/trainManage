<template>
  <div>
    <el-table  :data="tableData" style="width: 100%">
      <el-table-column type="expand">
        <template slot-scope="props">
          <el-form label-position="left" inline class="demo-table-expand">
            <el-form-item label="标题">
              <span>{{ props.row.subject }}</span>
            </el-form-item>
            <el-form-item label="发布企业">
              <span>{{ props.row.comName }}</span>
            </el-form-item>
            <el-form-item label="内容">
              <span>{{ props.row.content }}</span>
            </el-form-item>
            <el-form-item label="见习专业">
              <span >{{ props.row.major }}</span>
            </el-form-item>
            <el-form-item label="见习时间">
              <span>{{ props.row.recruitStart }} - {{props.row.recruitEnd}}</span>
            </el-form-item>
            <el-form-item label="负责教师" >
              <span v-if="!props.row.teacher"></span>
              <span v-if="props.row.teacher">
                <el-popover placement="top-start" width="400" trigger="hover">
                  <el-form label-position="left" inline class="demo-table-expand">
                    <el-form-item label="性别">
                      <span v-if="props.row.teacher.sex == 1">男</span>
                      <span v-if="props.row.teacher.sex == 2">女</span>
                    </el-form-item>
                    <el-form-item label="职务">
                      <span>{{ props.row.teacher.rank }}</span>
                    </el-form-item>
                    <el-form-item label="联系电话">
                      <span>{{ props.row.teacher.contactPhone }}</span>
                    </el-form-item>
                    <el-form-item label="邮箱">
                      <span>{{ props.row.teacher.email }}</span>
                    </el-form-item>
                  </el-form>
                  <el-button slot="reference">{{ props.row.teacher.teacherName}}</el-button>
                </el-popover>
              </span>
            </el-form-item>
            <el-form-item label="见习学生" style="margin-right: 0;margin-bottom: 0;width: 100%">
              <span v-if="!props.row.student"></span>
              <span v-if="props.row.student">
                      <el-popover placement="top-start" width="400" trigger="hover" v-for="st in props.row.student" :key="st.sno">
                        <el-form label-position="left" inline class="demo-table-expand">
                          <el-form-item label="性别">
                            <span v-if="st.sex == 1">男</span>
                            <span v-if="st.sex == 2">女</span>
                          </el-form-item>
                          <el-form-item label="专业">
                            <span>{{ st.major }}</span>
                          </el-form-item>
                          <el-form-item label="年级">
                            <span>{{ st.grade }}</span>
                          </el-form-item>
                          <el-form-item label="班级">
                            <span>{{ st.className }}</span>
                          </el-form-item>
                          <el-form-item label="学号">
                            <span>{{ st.sno }}</span>
                          </el-form-item>
                          <el-form-item label="出生日期">
                            <span>{{ st.bornDate }}</span>
                          </el-form-item>
                          <el-form-item label="联系电话">
                            <span>{{ st.contactPhone }}</span>
                          </el-form-item>
                          <el-form-item label="邮箱">
                            <span>{{ st.email }}</span>
                          </el-form-item>
                        </el-form>
                        <el-button slot="reference">{{ st.stuName}}</el-button>
                      </el-popover>
                    </span>
            </el-form-item>
            <el-form-item label="打卡时间">
              <span v-if="props.row.clockMor">{{ props.row.clockMor }} - {{props.row.clockEve}}</span>
            </el-form-item>
            <el-form-item label="打卡地点">
              <span v-if="props.row.lng">lng = {{ props.row.lng }} lat = {{ props.row.lat }} address = {{props.row.address}}</span>
            </el-form-item>
            <el-form-item label="打卡范围">
              <span v-if="props.row.scope">{{props.row.scope}}米</span>
            </el-form-item>
          </el-form>
        </template>
      </el-table-column>
      <el-table-column label="发布日期" width="180">
        <template slot-scope="scope">
          <i class="el-icon-time"></i>
          <span style="margin-left: 10px">{{ scope.row.time }}</span>
        </template>
      </el-table-column>
      <el-table-column label="标题" width="180">
        <template slot-scope="scope">
          <span>{{ scope.row.subject }}</span>
        </template>
      </el-table-column>
      <el-table-column label="发布企业" width="180">
        <template slot-scope="scope">
          <span>{{ scope.row.comName }}</span>
        </template>
      </el-table-column>
      <el-table-column label="见习时间" width="180">
        <template slot-scope="scope">
          <span>{{ scope.row.recruitStart }} - {{scope.row.recruitEnd}}</span>
        </template>
      </el-table-column>
      <el-table-column label="见习专业" width="180">
        <template slot-scope="scope">
          <span>{{ scope.row.major }}</span>
        </template>
      </el-table-column>
    </el-table>
  </div>
</template>

<script>
    import { common2 } from '../../../main'
    export default {
        name: 'TuComplete',
        props: ['compdata'],
        data () {
            return {
                tableData: []
            }
        },
        methods: {
            handleEdit (row) {
                this.$emit('compeletechild', 1, row)
            }
        },
        watch: {
            compdata (val, oldval) {
                this.tableData = val
            }
        },
        mounted () {
            if (this.compdata) {
                this.tableData = this.compdata
            }
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
</style>
