<template>
  <div>
    <el-tabs v-model="activeName" @tab-click="handleClick">
      <el-tab-pane label="进行中" name="first">
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
                <el-form-item label="发布时间">
                  <span>{{ props.row.time }}</span>
                </el-form-item>
                <el-form-item label="开始时间 — 结束时间">
                  <span>{{ props.row.date }}</span>
                </el-form-item>
                <el-form-item label="学生申请截止时间" v-if="props.row.status == 1">
                  <span>{{ props.row.studentEnddate }}</span>
                </el-form-item>
                <el-form-item label="招聘专业">
                  <span>{{ props.row.major }}</span>
                </el-form-item>
                <el-form-item label="招聘人数">
                  <span>{{ props.row.num }}</span>
                </el-form-item>
                <el-form-item label="审核状态">
                  <span v-show="props.row.status == 0">待审核</span>
                  <span v-show="props.row.status == 1" style="color: #67C23A">审核通过</span>
                  <span v-show="props.row.status == 2" style="color: #F56C6C">审核未通过</span>
                </el-form-item>
                <el-form-item label="负责教师" v-if="props.row.status == 1">
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
                <!--<el-form-item label="申请学生" v-if="props.row.status == 1">-->
                  <!--<span v-if="!props.row.applyst"></span>-->
                  <!--<span v-if="props.row.applyst">-->
                      <!--<el-popover placement="top-start" width="400" trigger="hover" v-for="st in props.row.applyst" :key="st.sno">-->
                        <!--<el-form label-position="left" inline class="demo-table-expand">-->
                          <!--<el-form-item label="性别">-->
                            <!--<span v-if="st.sex == 1">男</span>-->
                            <!--<span v-if="st.sex == 2">女</span>-->
                          <!--</el-form-item>-->
                          <!--<el-form-item label="专业">-->
                            <!--<span>{{ st.major }}</span>-->
                          <!--</el-form-item>-->
                          <!--<el-form-item label="年级">-->
                            <!--<span>{{ st.grade }}</span>-->
                          <!--</el-form-item>-->
                          <!--<el-form-item label="班级">-->
                            <!--<span>{{ st.className }}</span>-->
                          <!--</el-form-item>-->
                          <!--<el-form-item label="学号">-->
                            <!--<span>{{ st.sno }}</span>-->
                          <!--</el-form-item>-->
                          <!--<el-form-item label="出生日期">-->
                            <!--<span>{{ st.bornDate }}</span>-->
                          <!--</el-form-item>-->
                          <!--<el-form-item label="联系电话">-->
                            <!--<span>{{ st.contactPhone }}</span>-->
                          <!--</el-form-item>-->
                          <!--<el-form-item label="邮箱">-->
                            <!--<span>{{ st.email }}</span>-->
                          <!--</el-form-item>-->
                        <!--</el-form>-->
                        <!--<el-button slot="reference">{{ st.stuName}}</el-button>-->
                      <!--</el-popover>-->
                    <!--</span>-->
                <!--</el-form-item>-->
                <el-form-item label="实习学生" v-if="props.row.status == 1">
                  <span v-if="!props.row.applyst"></span>
                  <span v-if="props.row.applyst">
                      <el-popover placement="top-start" width="400" trigger="hover" v-for="st in props.row.applyst" :key="st.sno">
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
              <span v-show="scope.row.status == 1" style="color: #67C23A">审核通过</span>
              <span v-show="scope.row.status == 2" style="color: #F56C6C">审核未通过</span>
            </template>
          </el-table-column>
          <el-table-column label="操作">
            <template slot-scope="scope">
              <el-button type="success" icon="el-icon-check" circle @click="handleEnsure(scope.$index, scope.row)"></el-button>
              <el-button type="warning" icon="el-icon-close" circle @click="handleCancel(scope.$index, scope.row)"></el-button>
            </template>
          </el-table-column>
        </el-table>
        <el-dialog title="填写学生申请截止日期" :visible.sync="dialogVisible">
          <el-date-picker v-model="studentEnddate" type="date" placeholder="选择日期" value-format="yyyy-MM-dd">
          </el-date-picker>
          <div slot="footer" class="dialog-footer">
            <el-button @click="dialogVisible = false">取 消</el-button>
            <el-button type="primary" @click="dateEnsure">确 定</el-button>
          </div>
        </el-dialog>
      </el-tab-pane>



      <el-tab-pane label="已结束" name="second">
        <el-table  :data="tableData1" style="width: 100%" v-show="arrange == 0">
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
                <el-form-item label="发布时间">
                  <span>{{ props.row.time }}</span>
                </el-form-item>
                <el-form-item label="开始时间 — 结束时间">
                  <span>{{ props.row.date }}</span>
                </el-form-item>
                <el-form-item label="学生申请截止时间" v-if="props.row.status == 1">
                  <span>{{ props.row.studentEnddate }}</span>
                </el-form-item>
                <el-form-item label="招聘专业">
                  <span>{{ props.row.major }}</span>
                </el-form-item>
                <el-form-item label="招聘人数">
                  <span>{{ props.row.num }}</span>
                </el-form-item>
                <el-form-item label="审核状态">
                  <span v-show="props.row.status == 0">待审核</span>
                  <span v-show="props.row.status == 1" style="color: #67C23A">未安排</span>
                  <span v-show="props.row.status == 3" style="color: #67C23A">企业通过</span>
                  <span v-show="props.row.status == 4" style="color: #67C23A">企业未通过</span>
                  <span v-show="props.row.status == 5 && props.row.arrangeStuId" style="color: #67C23A">已安排</span>
                  <span v-show="props.row.status == 5 && !props.row.arrangeStuId" style="color: #67C23A">未安排</span>
                  <span v-show="props.row.status == 2" style="color: #F56C6C">审核未通过</span>
                </el-form-item>
                <el-form-item label="实习时间" v-show="props.row.status == 3">
                  <span>{{ props.row.recruitStart }} - {{props.row.recruitEnd}}</span>
                </el-form-item>
                <el-form-item label="打卡时间" v-show="props.row.status == 3">
                  <span>{{ props.row.clockMor }} - {{props.row.clockEve}}</span>
                </el-form-item>
                <el-form-item label="打卡地点" v-show="props.row.status == 3">
                  <span>lng = {{ props.row.lng }} lat = {{ props.row.lat }} address = {{props.row.address}}</span>
                </el-form-item>
                <el-form-item label="打卡范围" v-show="props.row.status == 3">
                  <span>{{props.row.scope}}米</span>
                </el-form-item>
                <el-form-item label="负责教师" v-show="props.row.status == 5 || props.row.status == 3">
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
                <el-form-item label="实习学生" v-show="props.row.status == 5 || props.row.status == 3">
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
          </el-table-column><el-table-column label="发布企业" width="180">
          <template slot-scope="scope">
            <span>{{ scope.row.comName }}</span>
          </template>
        </el-table-column>

          <el-table-column label="审核状态" width="180">
            <template slot-scope="scope">
              <span v-show="scope.row.status == 0">待审核</span>
              <span v-show="scope.row.status == 1" style="color: #67C23A">未安排</span>
              <span v-show="scope.row.status == 3" style="color: #67C23A">企业通过</span>
              <span v-show="scope.row.status == 4" style="color: #67C23A">企业未通过</span>
              <span v-show="scope.row.status == 5 && scope.row.arrangeStuId" style="color: #67C23A">已安排</span>
              <span v-show="scope.row.status == 5 && !scope.row.arrangeStuId" style="color: #67C23A">未安排</span>
              <span v-show="scope.row.status == 2" style="color: #F56C6C">审核未通过</span>
            </template>
          </el-table-column>
          <el-table-column label="操作">
            <template slot-scope="scope">
              <el-button type="primary" icon="el-icon-edit" circle @click="edit(scope.$index, scope.row)"></el-button>
            </template>
          </el-table-column>
        </el-table>
        <div class="block" v-show="arrange == 1">
          <el-form ref="form" :model="form" label-position="left" inline>
            <el-form-item label="标题" class="a">
              <span>{{ form.subject }}</span>
            </el-form-item>
            <el-form-item label="发布企业" class="a">
              <span>{{ form.comName }}</span>
            </el-form-item>
            <el-form-item label="内容" class="a">
              <span>{{ form.content }}</span>
            </el-form-item>
            <el-form-item label="发布时间" class="a">
              <span>{{ form.time }}</span>
            </el-form-item>
            <el-form-item label="开始时间 — 结束时间" class="a">
              <span>{{ form.date }}</span>
            </el-form-item>
            <el-form-item label="学生申请截止时间" class="a">
              <span>{{ form.studentEnddate }}</span>
            </el-form-item>
            <el-form-item label="招聘专业" class="b">
              <span>{{ form.major }}</span>
            </el-form-item>
            <el-form-item label="招聘人数">
              <span>{{ form.num }}</span>
            </el-form-item>
            <el-form-item label="负责教师" class="b">
              <el-select v-model="form.teacher" filterable placeholder="请选择">
                <el-option v-for="item in teacher" :key="item.id" :label="item.teacherName" :value="item.id">{{item.teacherName}}  {{item.job_num}}
                </el-option>
              </el-select>
            </el-form-item>
            <el-form-item label="实习学生" class="b">
              <div v-if="student">
                <el-row v-if="student['thisApply']">
                  <el-col :span="5">申请该实习的学生：</el-col>
                  <el-col :span="19">
                    <el-checkbox-group v-model="form.student">
                      <el-checkbox v-for="item in student['thisApply']" v-if="item.student" :key="item.student.id" :label="item.student.id">{{item.student.stuName}} {{item.student.sno}}
                        <el-popover placement="right" width="500" trigger="hover" v-show="item.recruit.length != 0">
                          <el-table :data="item.recruit" style="width: 100%">
                            <el-table-column width="150" prop="subject" label="标题">
                            </el-table-column>
                            <el-table-column width="200" prop="content" label="内容">
                            </el-table-column>
                            <el-table-column width="150" prop="comName" label="发布企业">
                            </el-table-column>
                          </el-table>
                          <el-button size="mini" slot="reference" icon="el-icon-view" circle></el-button>
                        </el-popover>
                      </el-checkbox>
                    </el-checkbox-group>
                  </el-col>
                </el-row>
                <el-row v-if="student['thisArrange']">
                  <el-col :span="5">安排该实习的学生：</el-col>
                  <el-col :span="19">
                    <el-checkbox-group v-model="form.student">
                      <el-checkbox v-for="item in student['thisArrange']" v-if="item.student" :key="item.student.id" :label="item.student.id">{{item.student.stuName}} {{item.student.sno}}
                        <el-popover placement="right" width="500" trigger="hover" v-show="item.recruit.length != 0">
                          <el-table :data="item.recruit" style="width: 100%">
                            <el-table-column width="150" prop="subject" label="标题">
                            </el-table-column>
                            <el-table-column width="200" prop="content" label="内容">
                            </el-table-column>
                            <el-table-column width="150" prop="comName" label="发布企业">
                            </el-table-column>
                          </el-table>
                          <el-button size="mini" slot="reference" icon="el-icon-view" circle></el-button>
                        </el-popover>
                      </el-checkbox>
                    </el-checkbox-group>
                  </el-col>
                </el-row>
                <el-row v-if="student['arrange']">
                  <el-col :span="5">安排其它实习的学生：</el-col>
                  <el-col :span="19">
                    <el-checkbox-group v-model="form.student">
                      <el-checkbox v-for="item in student['arrange']" v-if="item.student" :key="item.student.id" :label="item.student.id">{{item.student.stuName}} {{item.student.sno}}
                        <el-popover placement="right" width="500" trigger="hover" v-show="item.recruit.length != 0">
                          <el-table :data="item.recruit" style="width: 100%">
                            <el-table-column width="150" prop="subject" label="标题">
                            </el-table-column>
                            <el-table-column width="200" prop="content" label="内容">
                            </el-table-column>
                            <el-table-column width="150" prop="comName" label="发布企业">
                            </el-table-column>
                          </el-table>
                          <el-button size="mini" slot="reference" icon="el-icon-view" circle></el-button>
                        </el-popover>
                      </el-checkbox>
                    </el-checkbox-group>
                  </el-col>
                </el-row>
                <el-row v-if="student['unArrange']">
                  <el-col :span="5">未安排实习的学生：</el-col>
                  <el-col :span="19">
                    <el-checkbox-group v-model="form.student">
                      <el-checkbox v-for="item in student['unArrange']" v-if="item.student" :key="item.student.id" :label="item.student.id">{{item.student.stuName}} {{item.student.sno}}
                        <el-popover placement="right" width="500" trigger="hover" v-show="item.recruit.length != 0">
                          <el-table :data="item.recruit" style="width: 100%">
                            <el-table-column width="150" prop="subject" label="标题">
                            </el-table-column>
                            <el-table-column width="200" prop="content" label="内容">
                            </el-table-column>
                            <el-table-column width="150" prop="comName" label="发布企业">
                            </el-table-column>
                          </el-table>
                          <el-button size="mini" slot="reference" icon="el-icon-view" circle></el-button>
                        </el-popover>
                      </el-checkbox>
                    </el-checkbox-group>
                  </el-col>
                </el-row>
              </div>
            </el-form-item>
          </el-form>
          <el-button @click="cancelArran">取消</el-button>
          <el-button type="primary" @click="ensureArran">确定</el-button>
        </div>
      </el-tab-pane>
    </el-tabs>
  </div>
</template>

<script>
  import { common } from '../../../main'
  export default {
    name: 'RecruitTeacher',
    data () {
      return {
        activeName: 'first',
        tableData: [],
        tableData1: [],
        studentEnddate: '',
        dialogVisible: false,
        row: [],
        arrange: 0,
        teacher: [],
        student: [],
        applystudent:[],
        form: {
          id: '',
          teacher: '',
          subject: '',
          content: '',
          comName: '',
          time: '',
          date: '',
          studentEnddate: '',
          student: [],
          major: '',
          num: ''
        }
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
        this.$http.post('/practice/recruitment/data', {power: this.$store.getters.showPower}).then(function (res) {
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
            if (data[i].studentEnddate) {
              let ST = data[i].studentEnddate.split('-')
              let ST0 = new Date(ST[0], ST[1], ST[2])
              let ST1 = ST0.getTime()
              if (ST1 >= t1) {
                arr1.push(data[i])
              } else {
                arr2.push(data[i])
              }
            } else {
              if (T1 >= t1) {
                arr1.push(data[i])
              } else {
                arr2.push(data[i])
              }
            }
          }
          // that.getApplystu(arr1);
          that.tableData = arr1
          that.tableData1 = arr2
          console.log(arr1, arr2)
        })
      },
      handleEnsure (index, row) {
        this.dialogVisible = true
        this.row = row
      },
      dateEnsure () {
        this.dialogVisible = false
        let that = this
        this.$http.post('/practice/recruitment/modifystatus', { id: this.row.id, status: 1, date: this.studentEnddate }).then(function (res) {
          if (res.data.data == 1) {
            that.getData()
            common.$emit('change', '信息已修改！')
          }
        })
      },
      handleCancel (index, row) {
        let that = this
        this.$http.post('/practice/recruitment/modifystatus', { id: row.id, status: 2 }).then(function (res) {
          if (res.data.data == 1) {
            that.getData()
            common.$emit('change', '信息已修改！')
          }
        })
      },
      edit (index, row) {
        if (row.status == 0) {
          this.$notify({
            title: '警告',
            message: '该条招聘信息待审核，不能编辑！',
            type: 'warning'
          })
        } else if (row.status == 2) {
          this.$notify({
            title: '警告',
            message: '该条招聘信息审核未通过，不能编辑！',
            type: 'warning'
          })
        } else if (row.status == 3) {
          this.$notify({
            title: '警告',
            message: '该条招聘信息企业已通过，不能编辑！',
            type: 'warning'
          })
        } else if (row.status == 4) {
          this.$notify({
            title: '警告',
            message: '该条招聘信息企业未通过，不能编辑！',
            type: 'warning'
          })
        } else {
          let that = this
          this.$http.post('/practice/recruitment/gettstudent', { id: row.id }).then(function (res) {
            that.student = res.data.data
            console.log(res.data.data)
          })
          this.arrange = 1
          this.form.id = row.id
          this.form.teacher = row.teacher
          this.form.subject = row.subject
          this.form.content = row.content
          this.form.comName = row.comName
          this.form.date = row.date
          this.form.num = row.num
          this.form.major = row.major
          this.form.studentEnddate = row.studentEnddate
        }
      },
      ensureArran () {
        let that = this
        this.$http.post('/practice/recruitment/arrangest', { id: this.form.id, student: this.form.student }).then(function (res) {
          that.arrange = 0
          that.getData()
          common.$emit('change', '信息已修改！')
          console.log(res.data.data)
        })
        console.log(this.form.student)
      },
      cancelArran () {
        this.arrange = 0
      },
      getApplystu(data) {
        let id = [];
        for (let i=0;i<data.length;i++){
          id[i] = data[i]['id']
        }
        let that = this
        this.$http.post('/practice/recruitment/getapplystu', { id: id }).then(function (res) {
          console.log(res.data)
          that.applystudent = res.data.data
        })
        // console.log(that.applystudent)
        // this.$http.post('/practice/recruitment/gettstudent', { id: row.id }).then(function (res) {
        //   that.student = res.data.data
        //   console.log(res.data.data)
        // })
        // this.arrange = 1
        // this.form.id = row.id
        // this.form.teacher = row.teacher
        // this.form.subject = row.subject
        // this.form.content = row.content
        // this.form.comName = row.comName
        // this.form.date = row.date
        // this.form.num = row.num
        // this.form.major = row.major
        // this.form.studentEnddate = row.studentEnddate
      }
    },
    mounted () {
      this.getData()
      let that = this
      this.$http.post('/practice/recruitment/getteacher').then(function (res) {
        console.log(res.data)
        that.teacher = res.data.data
      })
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
  .a {
    margin-right: 0;
    margin-bottom: 0;
    width: 50%;
    float:left;
  }
  .b {
    margin-right: 0;
    margin-bottom: 0;
    width: 100%;
  }
</style>
