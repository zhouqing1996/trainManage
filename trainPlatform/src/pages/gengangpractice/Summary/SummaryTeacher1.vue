<template>
      <div class="display1" v-if="tag">
        <!--<button class="btn1">实习总结</button>-->
        <div class="display2">
          <div>
            <el-button style="margin-left: 20px" @click="back()">返回</el-button>
            <el-button type="primary" style="margin-left: 20px" @click="exportWd()">下载</el-button>
          </div>
          <div class="inforStyle">
            <!--基本信息-->
            <span class="tltleStyle"><i class="el-icon-document"></i>基本信息</span>
            <div class="distence">
              <span class="span">姓名:</span><span>{{student.stuName}}</span>
              <span class="span">学号:</span><span>{{student.sno}}</span>
              <span class="span">专业:</span><span>{{student.major}}</span>
              <span class="span">班级:</span><span>{{student.grade}}{{student.major}}{{student.class}}</span><br>
              <!--<span class="span">实习地点：</span><span>未定义</span>-->
              <!--<span class="span">实习时间:</span><span>未定义-未定义</span>-->
              <!--<span class="span">指导教师：</span><span>未定义</span>-->
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
            <span class="tltleStyle"><i class="el-icon-date"></i>实习总结</span>
            <div class="distence;span">
              <div v-for="(item,index) in summary">
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
                  <span style="color: #D68910;margin-top: 15px" class="span">企业评价</span><br>
                  <span class="span">内容:</span>
                  <span>
                    <el-input style="width: 60%" type="textarea" :disabled="mark == 'teacher'" :rows="1" placeholder="请输入内容" v-model="item.companyEvaluate">
                    </el-input>
                  </span>
                  <el-button size="mini" @click="submit(index,item.companyEvaluate,2)">确定</el-button>
                  <br>
                  <br>
                  <span style="color: #D68910;margin-top: 15px" class="span">教师评价</span><br>
                  <span class="span">内容:</span>
                  <span>
                    <el-input style="width: 60%" type="textarea" :rows="1" placeholder="请输入内容" :disabled="mark == 'company'" v-model="item.teacherEvaluate">
                    </el-input>
                  </span>
                  <el-button size="mini" @click="submit(index,item.teacherEvaluate,1)">确定</el-button>
                  <div style="margin-top: 10px">---------------------------------------------------
                    ---------------------------------------------------------------------------
                    --------------------------------------</div>
                </div>
              </div>
            </div>
            <div>
              <!--学校评价-->
              <span class="tltleStyle"><i class="el-icon-edit-outline"></i>企业总结评价</span>
              <div class="distence">
                <span class="span">内容:</span>
                <span>
                      <el-input style="width: 60%" type="textarea" :disabled="mark == 'teacher'" :rows="4" placeholder="请输入内容" v-model="comEndEva">
                      </el-input>
                    </span>
                <el-button size="mini" @click="evaluate(comEndEva,5)">确定</el-button>
              </div>
              <!--教师评价-->
              <span class="tltleStyle"><i class="el-icon-star-off"></i>学校总结评价</span>
              <div class="distence">
                <span class="span">内容:</span>
                <span>
                      <el-input style="width: 60%" type="textarea" :disabled="mark == 'company'" :rows="4" placeholder="请输入内容" v-model="majorEvaluate">
                      </el-input>
                      </span>
                <el-button size="mini" @click="evaluate(majorEvaluate,4)">确定</el-button>
              </div>
            </div>
          </div>
        </div>
      </div>
</template>

<script>
  import { common8 } from '../../../main'
  import { common9,common10 } from '../../../main'
  const cityOptions = ['周一', '周二', '周三', '周四', '周五', '周六', '周日']
  export default {
    name: 'SummaryTeacher1',
    props: ['student','mark'],
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
        stuId:'',
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
        gengangpractice: [],
        studentId: '',
        stuSurvey:[],
        summary: '',
        summaryContent: [],
        teacherEvaluate: '',
        summaryIndex: '',
        majorEvaluate:[],
        comEndEva:[],
        surveyInfo:[],
        tag:false,
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
      console.log(this.student)
      console.log(this.mark)
      this.summaryView()
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

      summaryView: function (index) {
        console.log(this.student['id'])
        console.log(this.tag)
        let that = this;
        that.$http.post('/gengangpractice/evaluate/summary', {
          stuId: that.student['id']
        }).then((res) => {
          console.log(res.data)
          that.summary = res.data.data[0];
          that.majorEvaluate = res.data.data[1]['majorEvaluate'];
          that.comEndEva = res.data.data[2]['comEndEva'];
          that.stuSurvey = res.data.data[3];
          for (var i=0;i<that.stuSurvey.length;i++){
            that.surveyInfo[i]=[];
            that.surveyInfo[i]['url']='/requirement/surveyview?id='+that.stuSurvey[i]['id']+'&flag=20&status=2'+'&stuId='+that.student['id'];
            that.surveyInfo[i]['data']='/requirement/datastatistics?id='+that.stuSurvey[i]['id']+'&flag=20'+'&stuId='+that.student['id'];
          }
          // this.listView = 1
          this.tag = true;
          console.log(that.surveyInfo)
          console.log(this.tag)
        })

      },
      submit: function (index, evaluate,flag) {
        this.$http.post('/gengangpractice/evaluate/insertsummary', {
          summaryId: this.summary[index]['id'],
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
        console.log(this.student['id'],evaluate,flag)
        let that=this;
        that.$http.post('/gengangpractice/evaluate/insertevaluate', {
          stuId:that.student['id'],
          evaluate: evaluate,
          flag:flag
        }).then((res) => {
          console.log(res)
          if (res.data.message == 1) {
            if(flag==4){
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
        if (this.mark == 'teacher'){
          common9.$emit('change',1)
        } else{
          common10.$emit('change',1)
        }
      },
      exportWd () {
        console.log(this.student)
        console.log(this.summary)
        this.$http.post('/gengangpractice/export/sumword', { stu: this.student, summary: this.summary }).then(function (res) {
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
