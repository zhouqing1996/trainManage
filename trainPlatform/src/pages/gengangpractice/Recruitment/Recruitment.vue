<template>
    <div>
      <el-tabs v-model="activeName" @tab-click="handleClick">
        <el-tab-pane label="进行中" name="first">
          <el-button type="primary" style="margin-bottom: 15px" @click="dialogVisible = true"><i class="el-icon-plus"></i>发布新招聘</el-button>
          <AddRecruit v-if="dialogVisible" v-on:child="listenTo" :dialogVisible="dialogVisible" :major="major" :grade="grade"></AddRecruit>
          <el-table  :data="tableData" style="width: 100%">
            <el-table-column type="expand">
              <template slot-scope="props">
                <el-form label-position="left" inline class="demo-table-expand">
                  <el-form-item label="标题">
                    <span v-show="props.$index != nowrow">{{ props.row.subject }}</span>
                    <el-input v-model="props.row.subject" placeholder="请输入内容" v-show="props.$index == nowrow"></el-input>
                  </el-form-item>
                  <el-form-item label="发布企业">
                    <span>{{ props.row.comName }}</span>
                  </el-form-item>
                  <el-form-item label="内容">
                    <span v-show="props.$index != nowrow">{{ props.row.content }}</span>
                    <el-input v-model="props.row.content" placeholder="请输入内容" v-show="props.$index == nowrow"></el-input>
                  </el-form-item>
                  <el-form-item label="发布时间">
                    <span v-show="props.$index != nowrow">{{ props.row.time }}</span>
                    <span v-show="props.$index == nowrow">{{ nowdate }}</span>
                  </el-form-item>
                  <el-form-item label="开始时间 — 结束时间">
                    <span v-show="props.$index != nowrow">{{ props.row.date }}</span>
                    <el-date-picker v-model="props.row.date" v-show="props.$index == nowrow" value-format="yyyy-MM-dd" type="daterange" range-separator="至" start-placeholder="开始日期" end-placeholder="结束日期">
                    </el-date-picker>
                  </el-form-item>
<!--                  /////-->
                  <el-form-item label="招聘年级">
                    <span v-show="props.$index != nowrow">{{ props.row.grade }}</span>
                    <el-select v-show="props.$index == nowrow" v-model="props.row.grade" placeholder="请选择">
                      <el-option v-for="item in grade" :key="item" :label="item" :value="item">
                      </el-option>
                    </el-select>
                  </el-form-item>
                  <el-form-item label="招聘专业">
                    <span v-show="props.$index != nowrow">{{ props.row.major }}</span>
                    <el-select v-show="props.$index == nowrow" v-model="props.row.major" placeholder="请选择">
                      <el-option v-for="item in major" :key="item.id" :label="item.major" :value="item.major">
                      </el-option>
                    </el-select>
                  </el-form-item>
                  <el-form-item label="招聘人数">
                    <span v-show="props.$index != nowrow">{{ props.row.num }}</span>
                    <el-input-number v-show="props.$index == nowrow" v-model="props.row.num" :min="1" :max="50" label="招聘人数"></el-input-number>人
                  </el-form-item>
                  <el-form-item label="审核状态" v-show="props.$index != nowrow">
                    <span v-show="props.row.status == 0">待审核</span>
                    <span v-show="props.row.status == 1 || props.row.status == 5" style="color: #67C23A">审核通过</span>
                    <span v-show="props.row.status == 2" style="color: #F56C6C">审核未通过</span>
                  </el-form-item>
                </el-form>
                <el-button size="mini" type="primary" v-show="props.row.status != 1 && props.row.status !=5 && props.$index != nowrow" @click="editFunc(props.$index)">编辑</el-button>
                <el-button size="mini" type="primary" v-show="props.row.status != 1 && props.row.status !=5 && props.$index == nowrow" @click="ensure(props.row)">确定</el-button>
                <el-button size="mini" type="primary" v-show="props.row.status != 1 && props.row.status !=5 && props.$index == nowrow" @click="cancel(props.row)">取消</el-button>
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
            <el-table-column label="审核状态" width="180">
              <template slot-scope="scope">
                <span v-show="scope.row.status == 0">待审核</span>
                <span v-show="scope.row.status == 1 || scope.row.status == 5" style="color: #67C23A">审核通过</span>
                <span v-show="scope.row.status == 2" style="color: #F56C6C">审核未通过</span>
              </template>
            </el-table-column>
            <el-table-column label="操作">
              <template slot-scope="scope">
                <el-button type="danger" icon="el-icon-delete" circle @click="handleDelete(scope.$index, scope.row)"></el-button>
              </template>
            </el-table-column>
          </el-table>
        </el-tab-pane>

        <el-tab-pane label="已结束" name="second">
          <el-table  :data="tableData1" style="width: 100%">
            <el-table-column type="expand">
              <template slot-scope="props">
                <el-form label-position="left" inline class="demo-table-expand">
                  <el-form-item label="标题">
                    <span v-show="props.$index != nowrow1">{{ props.row.subject }}</span>
                    <el-input v-model="props.row.subject" placeholder="请输入内容" v-show="props.$index == nowrow1"></el-input>
                  </el-form-item>
                  <el-form-item label="发布企业">
                    <span>{{ props.row.comName }}</span>
                  </el-form-item>
                  <el-form-item label="内容">
                    <span v-show="props.$index != nowrow1">{{ props.row.content }}</span>
                    <el-input v-model="props.row.content" placeholder="请输入内容" v-show="props.$index == nowrow1"></el-input>
                  </el-form-item>
                  <el-form-item label="发布时间">
                    <span v-show="props.$index != nowrow1">{{ props.row.time }}</span>
                    <span v-show="props.$index == nowrow1">{{ nowdate }}</span>
                  </el-form-item>
                  <el-form-item label="开始时间 — 结束时间">
                    <span v-show="props.$index != nowrow1">{{ props.row.date }}</span>
                    <el-date-picker v-model="props.row.date" v-show="props.$index == nowrow1" value-format="yyyy-MM-dd" type="daterange" range-separator="至" start-placeholder="开始日期" end-placeholder="结束日期">
                    </el-date-picker>
                  </el-form-item>
                  <el-form-item label="招聘年级">
                    <span v-show="props.$index != nowrow1">{{ props.row.grade }}</span>
                    <el-select v-show="props.$index == nowrow1" v-model="props.row.grade " placeholder="请选择">
                      <el-option v-for="item in grade " :key="item" :label="item " :value="item ">
                      </el-option>
                    </el-select>
                  </el-form-item>
                  <el-form-item label="招聘专业">
                    <span v-show="props.$index != nowrow1">{{ props.row.major }}</span>
                    <el-select v-show="props.$index == nowrow1" v-model="props.row.major" placeholder="请选择">
                      <el-option v-for="item in major" :key="item.id" :label="item.major" :value="item.major">
                      </el-option>
                    </el-select>
                  </el-form-item>
                  <el-form-item label="招聘人数">
                    <span v-show="props.$index != nowrow">{{ props.row.num }}</span>
                    <el-input-number v-show="props.$index == nowrow" v-model="props.row.num" :min="1" :max="50" label="招聘人数"></el-input-number>人
                  </el-form-item>
                  <el-form-item label="审核状态" v-show="props.$index != nowrow1">
                    <span v-show="props.row.status == 0">待审核</span>
                    <span v-show="props.row.status == 1 || props.row.status == 5" style="color: #67C23A">审核通过</span>
                    <span v-show="props.row.status == 2" style="color: #F56C6C">审核未通过</span>
                    <span v-show="props.row.status == 3"><i class="el-icon-check" style="margin-left: 20px"></i></span>
                    <span v-show="props.row.status == 4"><i class="el-icon-close" style="margin-left: 20px"></i></span>
                  </el-form-item>
                  <el-form-item label="实习时间" v-show="props.row.status == 3">
                    <span v-show="props.$index != nowrow1">{{ props.row.recruitStart }} - {{props.row.recruitEnd}}</span>
                  </el-form-item>
                  <el-form-item label="打卡时间" v-show="props.row.status == 3">
                    <span v-show="props.$index != nowrow1">{{ props.row.clockMor }} - {{props.row.clockEve}}</span>
                  </el-form-item>
                  <el-form-item label="打卡地点" v-show="props.row.status == 3">
                    <span v-show="props.$index != nowrow1">lng = {{ props.row.lng }} lat = {{ props.row.lat }} address = {{props.row.address}}</span>
                  </el-form-item>
                  <el-form-item label="打卡范围" v-show="props.row.status == 3">
                    <span v-show="props.$index != nowrow1">{{props.row.scope}}米</span>
                  </el-form-item>
                  <el-form-item label="负责教师" v-show="props.row.status == 1 || props.row.status == 3 || props.row.status == 4 || props.row.status == 5">
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
                  <el-form-item label="实习学生" v-show="props.row.status == 1 || props.row.status == 3 || props.row.status == 4 || props.row.status == 5">
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
                </el-form>
                <el-button size="mini" type="primary" v-show="props.row.status != 1 && props.row.status != 5 && props.$index != nowrow1 && props.row.status != 3 && props.row.status != 4" @click="editFunc1(props.$index)">编辑</el-button>
                <el-button size="mini" type="primary" v-show="props.row.status != 1 && props.row.status != 5 && props.$index == nowrow1 && props.row.status != 3 && props.row.status != 4" @click="ensure1(props.row)">确定</el-button>
                <el-button size="mini" type="primary" v-show="props.row.status != 1 && props.row.status != 5 && props.$index == nowrow1 && props.row.status != 3 && props.row.status != 4" @click="cancel1(props.row)">取消</el-button>
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
            <el-table-column label="审核状态" width="180">
              <template slot-scope="scope">
                <span v-show="scope.row.status == 0">待审核</span>
                <span v-show="scope.row.status == 1 || scope.row.status == 5" style="color: #67C23A">审核通过</span>
                <span v-show="scope.row.status == 2" style="color: #F56C6C">审核未通过</span>
                <span v-show="scope.row.status == 3"><i class="el-icon-check" style="margin-left: 20px"></i></span>
                <span v-show="scope.row.status == 4"><i class="el-icon-close" style="margin-left: 20px"></i></span>
              </template>
            </el-table-column>
            <el-table-column label="操作">
              <template slot-scope="scope">
                <el-button type="success" icon="el-icon-check" circle @click="handleEnsure(scope.$index, scope.row)"></el-button>
                <el-button type="warning" icon="el-icon-close" circle @click="handleCancel(scope.$index, scope.row)"></el-button>
                <el-button type="danger" icon="el-icon-delete" circle @click="handleDelete(scope.$index, scope.row)"></el-button>
              </template>
            </el-table-column>
          </el-table>
          <Map v-if="map" v-on:mapchild="listenMap" :map="map" :rowId="rowId"></Map>
        </el-tab-pane>
      </el-tabs>
    </div>
</template>

<script>
  import AddRecruit from './AddRecruit'
  import Map from './Map'
  import { common } from '../../../main'
    export default {
      name: 'Recruitment',
      components: {
        Map,
        AddRecruit
      },
      data () {
        return {
          mycom: {},
          activeName: 'first',
          tableData: [],
          tableData1: [],
          nowrow: -1,
          nowrow1: -1,
          major: [],
          grade: [],
          nowdate: '',
          flagData: [],
          flagData1: [],
          dialogVisible: false,
          map: false,
          rowId: -1,
          power: this.$store.getters.showPower
        }
      },
      methods: {
        handleClick () {},
        getDate () {
          let date = new Date()
          let year = date.getFullYear()
          let mon = date.getMonth() < 9 ? '0' + (date.getMonth() + 1) : date.getMonth() + 1
          let day = date.getDate() < 10 ? '0' + date.getDate() : date.getDate()
          return year + '-' + mon + '-' + day
        },
        getData () {
          let that = this
          this.$http.post('/gengangpractice/recruitment/major').then(function (res) {
            that.major = res.data.data
            console.log(33333)
            console.log(res.data.data)
          })
            this.$http.post('/gengangpractice/recruitment/grade').then(function (res) {
                that.grade = res.data.data
              console.log(44444)
                console.log(res.data.data)
            })
          this.$http.post('/gengangpractice/recruitment/data',{ power: this.power}).then(function (res) {
            console.log(55555)
            console.log(res.data.data)
            let t = that.getDate().split('-')
            let t0 = new Date(t[0], t[1], t[2])
            let t1 = t0.getTime()
            let data = res.data.data
            let arr1 = []
            let arr2 = []
            for (let i = 0; i < data.length; i++) {
              let T = data[i].endDate.split('-')
              let T0 = new Date(T[0], T[1], T[2])
              let T1 = T0.getTime()
              if (T1 >= t1) {
                arr1.push(data[i])
              } else {
                arr2.push(data[i])
              }
            }
            that.tableData = arr1
            that.tableData1 = arr2
            console.log(arr1, arr2)
          })
        },
        editFunc (index) {
          this.flagData = JSON.parse(JSON.stringify(this.tableData))
          this.nowdate = this.getDate()
          this.nowrow = index
        },
        editFunc1 (index) {
          this.flagData1 = JSON.parse(JSON.stringify(this.tableData1))
          this.nowdate = this.getDate()
          this.nowrow1 = index
        },
        ensure (row) {
          console.log(row)
          this.nowrow = -1
          let that = this
          this.$http.post('/gengangpractice/recruitment/modify', { row: row }).then(function (res) {
            that.getData()
            common.$emit('change', '信息已修改！')
            console.log(res.data.data)
          })
        },
        ensure1 (row) {
          this.nowrow1 = -1
          let that = this
          this.$http.post('/gengangpractice/recruitment/modify', { row: row }).then(function (res) {
            that.getData()
            common.$emit('change', '信息已修改！')
            console.log(res.data.data)
          })
        },
        cancel (row) {
          this.tableData = this.flagData
          this.nowrow = -1
        },
        cancel1 (row) {
          this.tableData1 = this.flagData1
          this.nowrow1 = -1
        },
        handleDelete (index, row) {
          if (row.status == 1 || row.status == 3 || row.status == 5) {
            this.$notify({
              title: '警告',
              message: '该条招聘信息已审核，不能删除！',
              type: 'warning'
            })
          } else {
            let that = this
            this.$http.post('/gengangpractice/recruitment/delete', { id: row.id }).then(function (res) {
              if (res.data.data == 1) {
                that.getData()
                common.$emit('change', '信息已修改！')
                that.$alert('删除成功！', '成功', {
                  confirmButtonText: '确定'
                })
              }
            })
          }
        },
        handleEnsure (index, row) {
          console.log(row.id)
          if (row.status == 0) {
            this.$notify({
              title: '警告',
              message: '该条招聘信息未审核！',
              type: 'warning'
            })
          } else if (row.status == 2) {
            this.$notify({
              title: '警告',
              message: '该条招聘信息审核未通过！',
              type: 'warning'
            })
          } else {
            this.map = true
            this.rowId = row.id
            // let that = this
            // this.$http.post('/yii/practice/recruitment/modstatus', { id: row.id, status: 3 }).then(function (res) {
            //   that.getData()
            //   common.$emit('change', '信息已修改！')
            //   console.log(res.data.data)
            // })
          }
        },
        handleCancel (index, row) {
          if (row.status == 0) {
            this.$notify({
              title: '警告',
              message: '该条招聘信息未审核！',
              type: 'warning'
            })
          } else if (row.status == 2) {
            this.$notify({
              title: '警告',
              message: '该条招聘信息审核未通过！',
              type: 'warning'
            })
          } else {
            let that = this
            this.$http.post('/gengangpractice/recruitment/modstatus', {id: row.id, status: 4}).then(function (res) {
              that.getData()
              common.$emit('change', '信息已修改！')
              console.log(res.data.data)
            })
          }
        },
        listenTo (visible) {
          console.log(visible)
          if (visible == 1) {
            this.dialogVisible = false
            this.getData()
            common.$emit('change', '信息已修改！')
          } else {
            this.dialogVisible = false
          }
        },
        listenMap (visible) {
          if (visible == 1) {
            this.map = false
            this.getData()
            common.$emit('change', '信息已修改！')
          } else {
            this.map = false
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
