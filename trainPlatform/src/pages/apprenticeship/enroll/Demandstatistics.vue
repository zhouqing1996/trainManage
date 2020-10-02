<template>
  <div style="margin-top: 10px;margin-left: 10px;margin-right: 10px;background-color: #fff;">
    <el-tabs v-model="activeName" type="card" @tab-click="handleClick">
      <el-tab-pane name="first">
        <span slot="label"><i class="el-icon-upload"></i> 默认统计</span>
        <div style="background-color: white;padding: 0px;">
          <div class="container" >
            <div class="qu-wrap">
              <header v-if="quData['surveyInfo']['title']">
                <span  @click="iterator = backBtn(); iterator.next()" ><i class="iconfanhui2"></i>返回</span>
                <p>{{ quData['surveyInfo']['title']}}</p>
              </header>
              <div class="qu-content">
                <div v-for="(item, index2) in quData.questions" :key="index2" style="margin-top: 20px">
                  <section class="qu-data">
                    <h4 style="color: #41A6FF;">{{ `Q${index2 + 1}&nbsp;&nbsp;&nbsp;${item.topic}`}}</h4>
                  </section>
                  <div :span="24" v-if="item['type']=='input'">
                    <template >
                      <el-table :data="data[index2]" border style="width: 60%;margin-top: 10px" size="mini" :span-method="objectSpanMethod">
                        <el-table-column type="index" label="序号" width="150px">
                        </el-table-column>
                        <el-table-column prop="com" label="公司名称" width=""></el-table-column>
                        <el-table-column prop="opt" label="所需人数" width=""></el-table-column>
                        <el-table-column label="合计：" width="">{{total[index2]}}</el-table-column>
                      </el-table>
                    </template>
                  </div>
                </div>
              </div>
              <footer>
                <!--<router-link tag="p" to="/requirement/surveylist" id="backBtn">返 回</router-link>-->
              </footer>
            </div>
          </div>
        </div>
      </el-tab-pane>
      <!--<el-tab-pane name="second">-->
        <!--<span slot="label"><i class="el-icon-message"></i> 答卷数据</span>-->
        <!--<div style="width: 80%;background-color: white;padding-top: 2%;margin-left:10%;padding-bottom:40px;">-->
          <!--<el-table-->
            <!--:data="sourceInfo"-->
            <!--border-->
            <!--style="width: 70%">-->
            <!--<el-table-column-->
              <!--type="index"-->
              <!--label="序号"-->
              <!--width="50">-->
            <!--</el-table-column>-->
            <!--<el-table-column-->
              <!--prop="subtime"-->
              <!--label="提交时间"-->
              <!--width="180">-->
            <!--</el-table-column>-->
            <!--<el-table-column-->
              <!--prop="city"-->
              <!--label="来源"-->
              <!--width="300">-->
            <!--</el-table-column>-->
            <!--<el-table-column  label="操作" width="">-->
              <!--<template scope="scope">-->
                <!--<p @click=answerDetail(scope.$index)>详情</p>-->
              <!--</template>-->
            <!--</el-table-column>-->
          <!--</el-table>-->
          <!--<el-dialog width="60%"  :visible.sync="dialogTableVisible">-->
            <!--<div class="container">-->
              <!--<div class="qu-wrap">-->
                <!--<header>-->
                  <!--<p><i class="iconicon" style="color: #01A6FE;font-size: 22px;"></i>{{quData['surveyInfo']['title']}}</p>-->
                <!--</header>-->
                <!--<div class="qu-content">-->
                  <!--<section class="qu-item" v-for="(item, index3) in quData['questions']" :key="index3">-->
                    <!--<div><h3>{{ `${index3 + 1}.&nbsp;&nbsp;${item.topic}` }}</h3></div>-->
                    <!--&lt;!&ndash;<div>&ndash;&gt;-->
                    <!--<template style="margin-left: 40px;margin-top: 40px" v-if="paper">-->
                      <!--<div  v-if="item.type === 'radio'" style="padding-left: 20px;padding-top: 2px;">-->
                        <!--{{item['options'][paper[index3]['content']]}}-->
                      <!--</div>-->
                    <!--</template>-->
                    <!--<template style="margin-left: 40px;margin-top: 40px" v-if="paper" >-->
                      <!--<div  v-if="item.type === 'checkbox'" style="padding-left: 20px;padding-top: 2px;">-->
                        <!--<p v-for="(item1,index4) in paper[index3]['content']" :key="index4">-->
                          <!--{{item['options'][item1]}}-->
                        <!--</p>-->
                      <!--</div>-->
                    <!--</template>-->
                    <!--<template style="margin-left: 40px;margin-top: 40px" v-if="paper">-->
                      <!--<div  v-if="item.type === 'input'||item.type === 'rate'||item.type === 'textarea'" style="padding-left: 20px;padding-top: 2px;">-->
                        <!--{{paper[index3]['content']}}-->
                      <!--</div>-->
                    <!--</template>-->
                    <!--&lt;!&ndash;</div>&ndash;&gt;-->
                    <!--<div></div>-->
                  <!--</section>-->
                <!--</div>-->
              <!--</div>-->
            <!--</div>-->
          <!--</el-dialog>-->
        <!--</div>-->
      <!--</el-tab-pane>-->
    </el-tabs>
    <div class="overlay" v-show="isShowPrompt">
      <div class="prompt-box">
        <header>
          <span>提示</span>
          <a href="javascript:;" @click="isShowPrompt = false">&times;</a>
        </header>
        <p>{{ promptText }}</p>
        <footer>
          <span @click="isShowPrompt = false; iterator && iterator.next()">确定</span>
          <span @click="isShowPrompt = false">取消</span>
        </footer>
      </div>
    </div>
  </div>
</template>

<script>
  import Store from '../../../store.js'
  import echarts from 'echarts/lib/echarts'
  import 'echarts/lib/chart/pie'
  import 'echarts/lib/component/tooltip'
  import 'echarts/lib/component/toolbox'
  import DataChart from "./chart/dataChart";

  export default {
    name: 'Statistics',
    components: {DataChart},
    data () {      return {
        index2:'',
        myChart_two: '',
        optionIndex: '',
        data: [],
        quData: {},
        totalnum: '',
        scale: '',
        ansPercent: [],
        activeName: 'first',
        paperInfo:[],
        sourceInfo:[],
        ansContent:[],
        dialogTableVisible: false,
        paper:'',
        flag:'',
        promptText: '',
        isShowPrompt: false,
        total:[]
      }
    },

    created () {
      this.id = this.$route.query.id;
      this.flag = this.$route.query.flag;
      this.stuId = this.$route.query.stuId;
      this.getData();
    },

    methods: {
      handleClick (tab, event) {
        console.log(tab.name)
        if (tab.name == 'first'){
          this.getData;
        } else if(tab.name == 'second'){
          this.getPaper();
        }
      },
      getData () {
        let that = this
        console.log(this.stuId)
        this.$http.post('/apprenticeship/require/statistics', {
          surveyId: that.id,
          stuId:that.stuId
        }).then(function (res) {
          console.log(res.data);
          if (res.data.message == 'success') {
            that.quData = res.data.data[0];
            that.comArr = res.data.data[1];
            for (var i = 0; i < that.quData['questions'].length; i++) {
              var tableData1 = []
              var type = that.quData['questions'][i]['type']
              that.total[i]=0;
              if(type === 'input'){
                for (var j = 0; j < that.quData['answerInfo'][i].length; j++) {
                  tableData1[j] = {};
                  tableData1[j].com = that.comArr[i][j];
                  tableData1[j].opt = that.quData['answerInfo'][i][j];
                  that.total[i] += parseInt(that.quData['answerInfo'][i][j]);
                }
              }
              that.data[i] = tableData1;
            }
          }
          console.log(that.data);
          console.log(that.total)
        })
      },

      getPaper () {
        let that = this;
        this.$http.post('/requirement/statistics/getpaper', {
          surveyId: that.id,
          stuId:that.stuId
        }).then(function (res) {
          console.log(res.data);
          that.paperInfo = res.data.data;
          that.sourceInfo = that.paperInfo['source'];
          that.ansContent = that.paperInfo['content'];
        })
      },

      showPrompt(text) {
        this.promptText = text;
        this.isShowPrompt = true;
      },

      answerDetail(index){
        console.log(index);
        console.log(this.ansContent);
        for (var i=0;i<this.ansContent[index].length;i++){
          if (!(this.ansContent[index][i]['content'] instanceof Array) ){
            this.ansContent[index][i]['content']=this.ansContent[index][i]['content'].split("---");
          }
        }
        this.paper = this.ansContent[index];
        // console.log(this.paper);
        this.dialogTableVisible = true;
      },

      objectSpanMethod({ row, column, rowIndex, columnIndex }) {
        // console.log(row, column, rowIndex, columnIndex)
        if (columnIndex === 3) {
          if (rowIndex % 2 === 0) {
            return {
              rowspan: 2,
              colspan: 1
            };
          } else {
            return {
              rowspan: 0,
              colspan: 0
            };
          }
        }
      },

      *backBtn() {
        let that = this;
        console.log(that.flag)
        yield that.showPrompt(`放弃答题回到主页吗？`);
        if (that.flag == 10){
          yield that.$router.push({path:'/tracking/tracksurvey'});
        }else if (that.flag == 20){
          yield that.$router.push({path:'/practice/teacher'});
        } else{
          yield that.$router.push({path:'/requirement/surveylist'});
        }
      }
    },

    watch: {
      $route(){
        this.id = this.$route.query.id;
        this.getData();
      }
    }
  }

</script>

<style scoped lang="scss">
  @import '../../../style/_Data.scss';
  @import '../../../style/_Fill.scss';
  @import '../../../common/css/override-element-ui.css';
  li {
    list-style: none;
  }
  * {
    padding: 0;
    margin: 0;
  }

  .tab-list  {
    text-align: center;
    line-height: 40px;
    font-family: "微软雅黑";
    height: 40px;
    border-bottom: none;
  }
  .tab-list  li {
    float: left;
    border: 1px solid #000;
    border-right: none;
    width: 100px;
    cursor: pointer;
  }
  .tab-list li:last-of-type {
    border-right: 1px solid #000;
  }
  .tab-content  li {

    width: 302px;
    height: 300px;
    border: 1px solid #000;
    text-align: center;
    line-height: 300px;
    margin-top: 0px;
  }
  .outerBar {
    display: inline-block;
    position: relative;
    width: 12rem;
    height: 1.2rem;
    margin: 1.6rem 0 0 0;
    font-size: 1.2rem;
    border: 1px solid #ccc;
  }

  .innerBar {
    display: block;
    width: 60%;
    height: 100%;
    background-color: $orange;
  }

  .el-table__header-wrapper{
    width: 130%;
  }

  #myChart{
    width: 400px;
    height: 400px;
  }

  .qu-content[data-v-545b9686] {
    padding: 2rem;
    border-top: 2px solid #bbb;
    border-bottom: 0px solid #bbb;
  }
</style>
