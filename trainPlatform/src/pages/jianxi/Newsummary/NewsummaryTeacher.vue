<template>
  <div>
    <div v-if="listView==0">
      <el-button type="primary" style="margin-bottom: 10px" @click="setTime()">设置提醒</el-button>
      <template>
        <el-table :data="practice" border style="width: 100%">
          <el-table-column prop="major" label="专业" width="180">
          </el-table-column>
          <el-table-column prop="sno" label="学号" width="180">
          </el-table-column>
          <el-table-column prop="stuName" label="姓名">
          </el-table-column>
          <el-table-column label="操作">
            <el-button slot-scope="scope" @click="summaryView(scope.$index)">查看见习总结</el-button>
          </el-table-column>
        </el-table>
      </template>
    </div>
    <div v-if="listView==1">
      <div class="display1">
        <button class="btn1">见习总结</button>
        <div class="display2">
          <div>
            <el-button style="margin-left: 20px" @click="back()">返回</el-button>
            <el-button type="primary" style="margin-left: 20px" @click="exportWd()">下载</el-button>
          </div>
          <div class="inforStyle">
            <!--基本信息-->
            <span class="tltleStyle"><i class="el-icon-document"></i>基本信息</span>
            <div class="distence">
              <span class="span">姓名:</span><span>{{summary.stuName}}</span>
              <span class="span">学号:</span><span>{{summary.sno}}</span>
              <span class="span">专业:</span><span>{{summary.major}}</span>
              <span class="span">班级:</span><span>未定义</span><br>
              <span class="span">实习地点：</span><span>未定义</span>
              <span class="span">实习时间:</span><span>未定义-未定义</span>
              <span class="span">指导教师：</span><span>未定义</span>
            </div>
            <span class="tltleStyle"><i class="el-icon-date"></i>评价量表</span>
            <div class="distence">
              <template>
                <el-table :data="surveyInfo" border style="width: 100%">
                  <el-table-column type="index" label="序号" width="180">
                  </el-table-column>
                  <el-table-column prop="url" label="量表链接" width="180">
                    <!--<a url="url"> 详情</a>-->
                    <template scope="scope">
                      <a @click="jump(scope.$index,1)">填写量表</a>
                    </template>
                  </el-table-column>
                  <el-table-column prop="data" label="统计数据">
                    <template scope="scope">
                      <a @click="jump(scope.$index,2)">查看统计</a>
                    </template>
                  </el-table-column>
                  <!--<el-table-column label="操作">-->
                  <!--<el-button slot-scope="scope" @click="summaryView()">查看实习总结</el-button>-->
                  <!--</el-table-column>-->
                </el-table>
              </template>
            </div>
            <!--实习总结-->
            <span class="tltleStyle"><i class="el-icon-date"></i>见习总结</span>
            <div class="distence;span">
              <div v-for="(item,index) in summaryContent">
                <div v-if="item.majorEvaluate==null">
                  <span class="span" style="color: #D68910;">第{{index+1}}次总结</span><br>
                  <span class="span">标题:</span><span>{{item.subject}}</span><br>
                  <span class="span">内容:</span>
                  <span>
                  <el-input style="width: 60%" type="textarea" :rows="4" :disabled="true" placeholder="请输入内容" v-model="item.content">
                  </el-input>
                </span>
                  <br>
                  <br>
                  <span style="color: #D68910;margin-top: 15px" class="span">教师评价</span><br>
                  <span class="span">内容:</span>
                  <span>
                    <el-input style="width: 60%" type="textarea" :rows="1" placeholder="请输入内容" :disabled="power.userkind==2" v-model="item.teacherEvaluate">
                    </el-input>
                  </span>
                  <el-button size="mini" @click="submit(index,item.teacherEvaluate,1)">确定</el-button>
                  <br>
                  <br>
                  <span style="color: #D68910;margin-top: 15px" class="span">企业评价</span><br>
                  <span class="span">内容:</span>
                  <span>
                    <el-input style="width: 60%" type="textarea" :disabled="power.userkind==1" :rows="1" placeholder="请输入内容" v-model="item.companyEvaluate">
                    </el-input>
                  </span>
                  <el-button size="mini" @click="submit(index,item.companyEvaluate,2)">确定</el-button>
                  <div style="margin-top: 10px">---------------------------------------------------
                    ---------------------------------------------------------------------------
                    --------------------------------------</div>
                </div>
              </div>
            </div>
            <div>
              <!--教师评价-->
              <span class="tltleStyle"><i class="el-icon-star-off"></i>教师评价</span>
              <div class="distence">
                <span class="span">内容:</span>
                <span>
                      <el-input style="width: 60%" type="textarea" :disabled="power.userkind==2" :rows="4" placeholder="请输入内容" v-model="majorEvaluate">
                      </el-input>
                      </span>
                <el-button size="mini" @click="evaluate(majorEvaluate,4)">确定</el-button>
              </div>
              <!--学校评价-->
              <span class="tltleStyle"><i class="el-icon-edit-outline"></i>学校评价</span>
              <div class="distence">
                <span class="span">内容:</span>
                <span>
                      <el-input style="width: 60%" type="textarea" :disabled="power.userkind==1" :rows="4" placeholder="请输入内容" v-model="comEndEva">
                      </el-input>
                    </span>
                <el-button size="mini" @click="evaluate(comEndEva,5)">确定</el-button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div v-if="listView == 2">
      <span><el-button @click="back2()">返回</el-button></span>
      <span><el-button type="primary" @click="addremind()">添加</el-button></span>
      <el-table key='mytable' :data="tableData" style="width: 100%;margin-top: 10px">
        <el-table-column label="重复" width="180">
          <template slot-scope="scope">
            <span style="margin-left: 2px" v-for="item in scope.row.repeat" :key="item">
              <span v-if="item == 1">每周一</span>
              <span v-if="item == 2">每周二</span>
              <span v-if="item == 3">每周三</span>
              <span v-if="item == 4">每周四</span>
              <span v-if="item == 5">每周五</span>
              <span v-if="item == 6">每周六</span>
              <span v-if="item == 7">每周七</span>
            </span>
          </template>
        </el-table-column>
        <el-table-column label="内容" width="180">
          <template slot-scope="scope">
            <span>{{scope.row.content}}</span>
          </template>
        </el-table-column>
        <el-table-column label="见习习" width="360">
          <template slot-scope="scope">
            <span style="margin-left: 2px" v-for="(item, index) in scope.row.recruit" :key="index">
              {{item}}
            </span>
          </template>
        </el-table-column>
        <el-table-column label="操作">
          <template slot-scope="scope">
            <el-button type="primary" size="mini" @click="handleEdit(scope.$index, scope.row)">编辑</el-button>
            <el-button type="danger" size="mini" @click="handleDelete(scope.row.id)">删除</el-button>
          </template>
        </el-table-column>
      </el-table>
      <el-dialog title="添加提醒" :visible.sync="dialogVisible">
        <el-form :model="form">
          <el-form-item label="内容" :label-width="formLabelWidth">
            <el-input v-model="form.content" autocomplete="off"></el-input>
          </el-form-item>
          <el-form-item label="重复" :label-width="formLabelWidth">
            <el-checkbox :indeterminate="isIndeterminate" v-model="checkAll" @change="handleCheckAllChange">全选</el-checkbox>
            <div style="margin: 15px 0;"></div>
            <el-checkbox-group v-model="form.checkedDays" @change="handleCheckedDaysChange">
              <el-checkbox v-for="city in cities" :label="city" :key="city">{{city}}</el-checkbox>
            </el-checkbox-group>
          </el-form-item>
          <el-form-item label="推送" :label-width="formLabelWidth">
            <el-select v-model="form.push" multiple placeholder="请选择">
              <el-option v-for="item in pushOptions" :key="item.label" :label="item.value" :value="item.label">
              </el-option>
            </el-select>
          </el-form-item>
        </el-form>
        <div slot="footer" class="dialog-footer">
          <el-button @click="dialogVisible = false">取 消</el-button>
          <el-button type="primary" @click="ensure">确 定</el-button>
        </div>
      </el-dialog>
      <el-dialog title="编辑提醒" :visible.sync="dialogVisible2">
        <el-form :model="form2">
          <el-form-item label="内容" :label-width="formLabelWidth">
            <el-input v-model="form2.content" autocomplete="off"></el-input>
          </el-form-item>
          <el-form-item label="重复" :label-width="formLabelWidth">
            <el-checkbox :indeterminate="isIndeterminate" v-model="checkAll" @change="handleCheckAllChange">全选</el-checkbox>
            <div style="margin: 15px 0;"></div>
            <el-checkbox-group v-model="form2.checkedDays" @change="handleCheckedDaysChange">
              <el-checkbox v-for="city in cities" :label="city" :key="city">{{city}}</el-checkbox>
            </el-checkbox-group>
          </el-form-item>
          <el-form-item label="推送" :label-width="formLabelWidth">
            <el-select v-model="form2.push" multiple placeholder="请选择">
              <el-option v-for="item in pushOptions2" :key="item.label" :label="item.value" :value="item.label">
              </el-option>
            </el-select>
          </el-form-item>
        </el-form>
        <div slot="footer" class="dialog-footer">
          <el-button @click="dialogVisible2 = false">取 消</el-button>
          <el-button type="primary" @click="ensure2(form2.id)">确 定</el-button>
        </div>
      </el-dialog>
    </div>
  </div>
</template>

<script>
    import { common8 } from '../../../main'
    const cityOptions = ['周一', '周二', '周三', '周四', '周五', '周六', '周日']
    export default {
        name: 'NewsummaryTeacher',
        data () {
            return {
                power: this.$store.getters.showPower,
                dialogVisible2: false,
                formLabelWidth: '50px',
                checkAll: false,
                cities: cityOptions,
                isIndeterminate: true,
                pushOptions: [],
                pushOptions2: [],
                stuId: '',
                form: {
                    checkedDays: [],
                    content: '',
                    push: []
                },
                form2: {
                    id: '',
                    checkedDays: [],
                    content: '',
                    push: []
                },
                dialogVisible: false,
                tableData: [],
                clock: true,
                listView: 0,
                info: [],
                practice: [],
                studentId: '',
                stuSurvey:[],
                summary: '',
                summaryContent: [],
                teacherEvaluate: '',
                summaryIndex: '',
                majorEvaluate:[],
                comEndEva:[],
                surveyInfo:[],
                infor: {
                    name: '张三',
                    studentId: '123321',
                    school: '华中师范大学',
                    class: '1班',
                    major: '学前教育',
                    place: '华师附属幼儿园',
                    startTime: '2019/1/1',
                    endTime: '2020/1/1',
                    teacher: '李四',
                    summary: [],
                    teacherEvaluation: '教师评价',
                    schoolEvaluation: '学校评价'
                }
            }
        },
        created () {
            this.getdata()
        },
        methods: {
            jump (index,flag) {
                console.log(flag)
                let url;
                if (flag==1){
                    url = this.surveyInfo[index]['url']
                } else if(flag==2){
                    url = this.surveyInfo[index]['data']
                }
                console.log(url)
                this.$router.push({ path: url})
            },
            getdata: function () {
                let that = this
                this.$http.post('/probation/assess/getdata').then(function (res) {
                    console.log(111)
                    console.log(res.data)
                    that.info = res.data.data
                    that.stuInfo = that.info['stuInfo']
                    that.practice = []
                    for (var i = 0; i < that.stuInfo.length; i++) {
                        that.practice[i] = {}
                        that.practice[i].stuName = that.stuInfo[i][0]['stuName']
                        that.practice[i].sno = that.stuInfo[i][0]['sno']
                        that.practice[i].major = that.stuInfo[i][0]['major']
                    }
                })
                this.$http.post('/probation/remind/getremind').then(function (res) {
                    that.tableData = res.data.data
                })
            },
            summaryView: function (index) {
                this.summary = this.practice[index]
                this.summaryContent = this.info['summary'][index]
                this.stuId = this.info.summary[index][0]['stuId']
                this.summaryIndex = this.info.summary[index]
                let that = this;
                that.$http.post('/probation/assess/endsummary', {
                    stuId: that.stuId
                }).then((res) => {
                    console.log(res.data)
                    that.majorEvaluate = res.data.data[0]['majorEvaluate'];
                    that.comEndEva = res.data.data[1]['comEndEva'];
                    that.stuSurvey = res.data.data[2];
                    for (var i=0;i<that.stuSurvey.length;i++){
                        that.surveyInfo[i]=[];
                        that.surveyInfo[i]['url']='/requirement/surveyview?id='+that.stuSurvey[i]+'&flag=20&status=2'+'&stuId='+this.stuId;
                        that.surveyInfo[i]['data']='/requirement/datastatistics?id='+that.stuSurvey[i]+'&flag=20'+'&stuId='+this.stuId;
                    }


                })

            },
            submit: function (index, evaluate,flag) {
                this.$http.post('/probation/assess/insertsummary', {
                    summaryId: this.summaryIndex[index]['id'],
                    evaluate: evaluate,
                    flag:flag
                }).then((res) => {
                    if (res.data.message == 1) {
                        if(flag==1){
                            alert('教师评语添加成功！')
                        }else{
                            alert('企业评语添加成功！')
                        }
                    } else {
                        alert('教师评语添加失败！')
                    }
                })
            },
            evaluate: function (evaluate,flag) {
                let that=this;
                that.$http.post('/probation/assess/insertevaluate', {
                    stuId:that.stuId,
                    evaluate: evaluate,
                    flag:flag
                }).then((res) => {
                    if (res.data.message == 1) {
                        if(flag==1){
                            alert('教师总结评价添加成功！')
                        }else{
                            alert('企业总结评价添加成功！')
                        }
                    } else {
                        alert('总结评价添加失败！')
                    }
                })
            },
            back: function () {
                this.listView = 0
            },
            exportWd () {
                console.log(this.summary, this.summaryContent)
                this.$http.post('/probation/export/sumword', { stu: this.summary, summary: this.summaryContent }).then(function (res) {
                    console.log('/download' + res.data.data)
                    window.open('/download' + res.data.data, '_blank')
                })
            },
            setTime () {
                this.listView = 2
            },
            back2 () {
                this.listView = 0
            },
            handleEdit (index, row) {
                let that = this
                this.$http.post('/probation/remind/getpush').then(function (res) {
                    that.pushOptions2 = res.data.data
                })
                this.dialogVisible2 = true
                this.form2.content = row.content
                this.form2.id = row.id
                this.form2.push = []
                var a = []
                for (var i = 0; i < row.repeat.length; i++) {
                    if (row.repeat[i] == 1) {
                        a.push('周一')
                    } else if (row.repeat[i] == 2) {
                        a.push('周二')
                    } else if (row.repeat[i] == 3) {
                        a.push('周三')
                    } else if (row.repeat[i] == 4) {
                        a.push('周四')
                    } else if (row.repeat[i] == 5) {
                        a.push('周五')
                    } else if (row.repeat[i] == 6) {
                        a.push('周六')
                    } else if (row.repeat[i] == 7) {
                        a.push('周日')
                    }
                }
                this.form2.checkedDays = a
            },
            handleDelete (id) {
                let that = this
                this.$http.post('/probation/remind/deletremind', { id: id }).then(function (res) {
                    that.tableData = res.data.data
                    console.log(res.data.data)
                })
            },
            addremind () {
                this.dialogVisible = true
                let that = this
                this.$http.post('/probation/remind/getpush').then(function (res) {
                    that.pushOptions = res.data.data
                    console.log(res.data.data)
                })
            },
            handleCheckAllChange (val) {
                this.form.checkedDays = val ? cityOptions : []
                this.isIndeterminate = false
            },
            handleCheckedDaysChange (value) {
                let checkedCount = value.length
                this.checkAll = checkedCount === this.cities.length
                this.isIndeterminate = checkedCount > 0 && checkedCount < this.cities.length
            },
            ensure () {
                this.dialogVisible = false
                let that = this
                this.$http.post('/probation/remind/addremind', { form: this.form }).then(function (res) {
                    that.tableData = res.data.data
                    console.log(res.data.data)
                })
            },
            ensure2 () {
                this.dialogVisible2 = false
                let that = this
                this.$http.post('/probation/remind/editremind', { form: this.form2 }).then(function (res) {
                    that.tableData = res.data.data
                    console.log(res.data.data)
                })
            }
        }
    }
</script>

<style scoped>
  .display1 {
    /*  padding-left: 20px;*/
    padding-top: 10px;
    width: 90%;
  }

  .display2 {
    border: solid 2px #e0e0e0;
    height: auto;
    /*text-align: center;*/
    width: 90%;
    padding-left: 10px;
    padding-right: 10px;
    padding-top:20px;
    background-color: #fff;
  }

  .btn1 {
    font-size: 18px;
    padding: 10px 10px;
    border-top-left-radius: 3px;
    border-top-right-radius: 3px;
    border: 1px solid #ccc;
    cursor: pointer;
    background: #f0f0f0;
    margin-bottom: -1px;
    color: #000;
    /*margin-right: 0px;*/
  }
  .inforStyle{
    margin-top: 20px;
  }
  .distence{
    /*padding-left: 20px;*/
    padding-top: 10px;
    padding-bottom: 20px;
  }
  .span{
    padding-left: 20px;
    color: #3498DB;
    font-weight: bold;
    padding-top: 10px;
  }
  .tltleStyle{
    color: #2E86C1;font-weight: bold;font-size: 18px;
  }
</style>
