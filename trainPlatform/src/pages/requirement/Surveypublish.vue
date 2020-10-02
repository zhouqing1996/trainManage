<template>
  <div style="width: 80%;margin:auto;">
      <el-tabs v-model="activeName" type="card" @tab-click="handleClick">
        <el-tab-pane name="first">
          <span slot="label"><i class="el-icon-upload"></i> 链接分享</span>
          <div style="background-color: white;padding: 5%;padding-left: 5%;height: 300px;">
            <!--<div style="width: 10%" id="qrcode"></div>-->
            <div style="width: 150px;height: 150px;float: left" >
              <div style="border: 3px;" id="qrcode"></div>
              <div style="padding-left:8px">问卷二维码</div>
            </div>
            <div style="float: left">
              <span style="font-size: 18px;font-weight: 700;font-family: 'Microsoft YaHei UI'">问卷链接与二维码</span>
              <div style="margin-top: 10px">
                <!--<el-input style="width: 600px" v-model="form.link"></el-input>-->
                <el-input
                  type="textarea"
                  style="width: 400px"
                  :rows="2"
                  v-model="form.link">
                </el-input>
              </div>

              <div style="margin-top: 15px">
                <img style="width: 20px;height: 25px" :src="require('../../common/images/qq.png')" @click="share(1)">
                <img style="width: 20px;height: 25px;margin-left: 5px" :src="require('../../common/images/Qzone.png')" @click="share(2)">
                <img style="width: 25px;height: 25px;margin-left: 5px" :src="require('../../common/images/weibo.png')" @click="share(3)">
              </div>
              <!--<el-form-item label="问卷链接与二维码"></el-form-item>-->
              <!--<el-form ref="form" :model="form" label-width="150px">-->
                <!--<el-form-item>-->

                  <!--<img :src="require('../../common/images/qq.png')" @click="share(1)">-->
                  <!--<img :src="require('../../common/images/qzone.png')" @click="share(2)">-->
                  <!--<img :src="require('../../common/images/weibo.png')" @click="share(3)">-->
                <!--</el-form-item>-->
                <!--<el-form-item>-->
                  <!--<el-button type="primary" @click="onSubmit">发送邮件</el-button>-->
                  <!--<el-button>取消</el-button>-->
                <!--</el-form-item>-->
              <!--</el-form>-->
            </div>
          </div>
        </el-tab-pane>
        <el-tab-pane >
          <span slot="label"><i class="el-icon-message"></i> 邮件推送</span>
          <div style="width: 100%;background-color: white;padding-top: 2%">
            <el-form ref="form" :model="form" label-width="80px">
              <el-form-item style="width: 30%" label="发送邮箱">
                <el-input v-model="form.post"></el-input>
              </el-form-item>
              <el-form-item label="邮件内容">
                <el-input
                  type="textarea"
                  style="width: 50%"
                  :rows="3"
                  placeholder="请输入邮件内容"
                  v-model="form.content">
                </el-input>
              </el-form-item>
              <el-form-item style="width: 50%" label="发送群体">
                <el-button @click="getcompany()" type="text">企业用户</el-button>
                <el-button @click="getteacher()" type="text">教师用户</el-button>
                <el-button @click="getclass()" type="text">学生用户</el-button>
              </el-form-item>
              <el-form-item v-if="postType=='company'" label="企业名称">
                <el-checkbox-group v-model="form.address">
                  <el-checkbox v-for="(item,key) in companyList" :label="item.comEmail" name="address">
                  {{item.comName}}</el-checkbox>
                </el-checkbox-group>
              </el-form-item>
              <el-form-item v-if="postType=='teacher'" label="教师信息">
                <el-checkbox-group v-model="form.address">
                  <el-checkbox v-for="(item,key) in teacherList" :label="item.email" name="address">
                    {{item.teacherName}}</el-checkbox>
                </el-checkbox-group>
              </el-form-item>
              <el-form-item v-if="postType=='student'" label="班级信息">
                <el-checkbox-group v-model="form.address">
                  <el-checkbox v-for="(item,key) in classList" :label="item.id" name="address">
                    {{item.grade+item.major+item.className}}
                  </el-checkbox>
                </el-checkbox-group>

                <!--<el-dialog title="班级成员" :visible.sync="dialogTableVisible">-->
                  <!--<el-checkbox-group v-model="form.type">-->
                    <!--<el-checkbox v-for="item in stuList" :label="item.stuName"  name="type">-->
                        <!--<div class="top">-->
                          <!--<el-tooltip class="item" effect="dark" :content="'学号：'+item.sno" placement="top">-->
                            <!--<el-button>{{item.stuName}}</el-button>-->
                          <!--</el-tooltip>-->
                        <!--</div>-->
                    <!--</el-checkbox>-->
                  <!--</el-checkbox-group>-->
                <!--</el-dialog>-->
              </el-form-item>

              <el-form-item style="padding-bottom: 2%">
                <el-button type="primary" @click="onSubmit">发送邮件</el-button>
                <el-button>取消</el-button>
              </el-form-item>
            </el-form>
          </div>
        </el-tab-pane>
        <el-tab-pane v-if="flag == 20">
          <span slot="label"><i class="el-icon-setting"></i> 学生推送</span>
          <div style="width: 100%;background-color: white;padding-top: 2%;padding-bottom:20px">
            <el-form ref="form" :model="practice" label-width="80px">
              <el-form-item label="内容">
                <el-input type="textarea" style="width: 50%" :rows="3" placeholder="请输入邮件内容" v-model="practice.content">
                </el-input>
              </el-form-item>
              <el-form-item style="width: 50%" label="发送群体">
                <el-select v-model="practice.push" multiple placeholder="请选择" @visible-change="visibleChange">
                  <el-option v-for="item in pushOptions" :key="item.label" :label="item.value" :value="item.label">
                  </el-option>
                </el-select>
              </el-form-item>
              <el-form-item style="padding-bottom: 2%">
                <el-button type="primary" @click="practiceSubmit">发送</el-button>
              </el-form-item>
            </el-form>
          </div>
        </el-tab-pane>
        <el-tab-pane v-if="flag == 20">
          <span slot="label"><i class="el-icon-setting"></i> 量表分配 </span>
          <div style="width: 100%;background-color: white;padding-top: 2%;padding-bottom:20px">
            <el-form ref="form" :model="practice" label-width="80px">
              <el-form-item style="width: 50%" label="群体设置">
                <el-select v-model="practice.push" multiple placeholder="请选择" @visible-change="visibleChange">
                  <el-option v-for="item in pushOptions" :key="item.label" :label="item.value" :value="item.label">
                  </el-option>
                </el-select>
              </el-form-item>
              <el-form-item style="padding-bottom: 2%">
                <el-button type="primary" @click="practiceSet">设置</el-button>
              </el-form-item>
            </el-form>
          </div>
        </el-tab-pane>
      </el-tabs>
  </div>
</template>

<script>
    import QRCode from 'qrcodejs2'
    export default {
        name: "Surveypublish",
        data() {
          return {
            pushOptions: [],
            practice: {
              content: '',
              push: []
            },
            flag: -1,
            activeName:'first',
            classList:[],
            stuList:[],
            stuEmail:[],
            companyList:[],
            teacherList:[],
            postType:'',
            textarea:'',
            // qrcode:'',
            activeName2: 'first',
            dialogTableVisible: false,
            dialogTableVisible1: false,
            form: {
              post: '',
              content: '',
              link:'',
              date1: '',
              date2: '',
              delivery: false,
              address: [],
              type:[],
              resource: '',
              desc: ''
            },
            formLabelWidth: '120px'
          };
        },

        created() {
          this.id = this.$route.query.id;
          this.flag = this.$route.query.flag
          this.getdata();
        },

        methods: {
          getdata:function(){
            this.form.post='614490691@qq.com';
            this.form.content='有一份调查请您填写，链接为：'+'http://phpitem.grouptong.top/trainManage/surveyView/requirement/surveyviewnew?id='+this.id+'&&status=2';
            this.form.link = 'http://phpitem.grouptong.top/trainManage/surveyView/requirement/surveyviewnew?id='+this.id+'&&status=2';
            if (this.flag == 20) {
              let that = this
              this.$http.post('/practice/remind/getpush').then(function (res) {
                that.pushOptions = res.data.data
                console.log(res.data.data)
              })
              this.$http.post('/requirement/publish/getpractice', { id: this.id }).then(function (res) {
                that.practice.content = res.data.data.content
                that.practice.push = res.data.data.push
                console.log(res.data.data)
              })
            }
          },
          getclass:function(){
            let that=this;
            this.$http.get('/requirement/publish/getdata?page=1').then(function (res) {
              console.log(res.data);
              that.classList=res.data.data[0];
              that.postType = 'student' ;
            })
          },
          getstudent:function(classId){
            let that=this;
            this.$http.post('/requirement/publish/getstudent',{
              classId:classId
            }).then(function (res) {
              that.stuEmail = res.data.data;
              that.$http.post('/requirement/publish/getemail',{
                address:that.stuEmail,
                content:that.form.content
              }).then(function (res) {
                alert("邮件发送成功！");
                location.reload();
              })
            })
          },
          getcompany:function(){
            let that=this;
            this.$http.post('/requirement/publish/getcompany').then(function (res) {
              console.log(res.data);
              that.companyList = res.data.data;
              that.postType = 'company' ;
            })
          },

          getteacher:function(){
            let that=this;
            this.$http.post('/requirement/publish/getteacher').then(function (res) {
              console.log(res.data);
              that.teacherList = res.data.data;
              that.postType = 'teacher' ;
            })
          },

          handleClick(tab, event) {
            console.log(tab, event);
          },
          onSubmit() {
            if (this.postType == 'company'||this.postType == 'teacher'){
              this.$http.post('/requirement/publish/getemail',{
                address:this.form.address,
                content:this.form.content
              }).then(function (res) {
                alert("邮件发送成功！");
                // location.reload();
              })
            } else if(this.postType == 'student'){
              this.getstudent(this.form.address);
            }
          },
          share(shareType){
            var shareUrl;
            if (shareType == 1){
              shareUrl='https://connect.qq.com/widget/shareqq/index.html?url='+ 'http://phpitem.grouptong.top/trainManage/surveyView/requirement/surveyviewnew?id='+ this.id+'&&status=2'+'&title=Hi，你好，这是我在智能问卷调查系统上创建的问卷，烦请填写！';
            }else if(shareType == 2){
              shareUrl='http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url='+ encodeURIComponent('http://phpitem.grouptong.top/trainManage/surveyView/requirement/surveyviewnew?id='+ this.id+'&&status=2')+'&site=智能问卷调查系统'+'&title=Hi，你好，这是我在智能问卷调查系统上创建的问卷，烦请填写！ ';
            }else if(shareType == 3){
              shareUrl='http://service.weibo.com/share/share.php?title='+ 'http://phpitem.grouptong.top/trainManage/surveyView/requirement/surveyviewnew?id='+ this.id+'&&status=2'+'&appkey=智能问卷调查系统';
            }
            window.open(shareUrl);
          },
          qrcode () {
            let qrcode = new QRCode('qrcode', {
              width: 100,
              height: 100, // 高度
              text: 'http://phpitem.grouptong.top/trainManage/surveyView/requirement/surveyviewnew?id='+ this.id + '&&status=2' // 二维码内容
              // render: 'canvas' // 设置渲染方式（有两种方式 table和canvas，默认是canvas）
              // background: '#f0f'
              // foreground: '#ff0'
            })
          },
          practiceSubmit () {
            console.log(this.practice)
            let that = this
            this.$http.post('/requirement/publish/submitpractice', { practice: this.practice, id: this.id }).then(function (res) {
              that.$message({
                message: '发送成功！',
                type: 'success'
              })
              that.practice.content = res.data.data.content
              that.practice.push = res.data.data.push
              console.log(res.data.data)
              that.$http.post('/practice/remind/getpush').then(function (res) {
                that.pushOptions = res.data.data
                console.log(res.data.data)
              })
            })
          },
          practiceSet () {
            let that = this
            this.$http.post('/requirement/publish/setpractice', { practice: this.practice, id: this.id }).then(function (res) {
              that.$message({
                message: '发送成功！',
                type: 'success'
              })
            })
          },
          visibleChange (val) {
            if (val == true) {
              this.practice.push = []
            }
            console.log(val)
          }
        },
      mounted(){
          this.qrcode();
      }
    }
</script>

<style scope>
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
</style>
