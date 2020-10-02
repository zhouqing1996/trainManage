<template>
  <div style="width: 80%;margin:auto;">
    <el-tabs v-model="activeName" type="card" @tab-click="handleClick">
      <el-tab-pane name="first">
        <span slot="label"><i class="el-icon-upload"></i> 链接分享</span>
        <div style="background-color: white;padding: 5%;padding-left: 5%;height: 300px;">
          <div style="width: 150px;height: 150px;float: left" >
            <div style="border: 3px;" id="qrcode"></div>
            <div style="padding-left:8px">问卷二维码</div>
            <el-button @click="backTo" style="margin-top: 30px">返回</el-button>
          </div>
          <div style="float: left">
            <span style="font-size: 18px;font-weight: 700;font-family: 'Microsoft YaHei UI'">问卷链接与二维码</span>
            <div style="margin-top: 10px">
              <el-input style="width: 350px" v-model="form.link"></el-input>
            </div>

            <div style="margin-top: 15px">
              <img style="width: 20px;height: 25px" :src="require('../../../../common/images/qq.png')" @click="share(1)">
              <img style="width: 20px;height: 25px;margin-left: 5px" :src="require('../../../../common/images/Qzone.png')" @click="share(2)">
              <img style="width: 25px;height: 25px;margin-left: 5px" :src="require('../../../../common/images/weibo.png')" @click="share(3)">
            </div>
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
              <span :key="item.id" v-for="item in row.student" style="margin-right: 10px">{{item.stuName}}</span>
            </el-form-item>
            <el-form-item style="padding-bottom: 2%">
              <el-button type="primary" @click="onSubmit">发送邮件</el-button>
              <el-button @click="backTo">返回</el-button>
            </el-form-item>
          </el-form>
        </div>
      </el-tab-pane>
      <el-tab-pane v-if="flag == 20">
        <span slot="label"><i class="el-icon-setting"></i> 学生推送</span>
        <div style="width: 100%;background-color: white;padding-top: 2%;padding-bottom:20px">
          <el-form ref="form" :model="gengangpractice" label-width="80px">
            <el-form-item label="内容">
              <el-input type="textarea" style="width: 50%" :rows="3" placeholder="请输入邮件内容" v-model="gengangpractice.content">
              </el-input>
            </el-form-item>
            <el-form-item style="width: 50%" label="发送群体">
              <span :key="item.id" v-for="item in row.student" style="margin-right: 10px">{{item.stuName}}</span>
            </el-form-item>
            <el-form-item style="padding-bottom: 2%">
              <el-button type="primary" @click="gengangpracticeSubmit">发送</el-button>
              <el-button @click="backTo">返回</el-button>
            </el-form-item>
          </el-form>
        </div>
      </el-tab-pane>
      <el-tab-pane v-if="flag == 20">
        <span slot="label"><i class="el-icon-setting"></i> 量表分配 </span>
        <div style="width: 100%;background-color: white;padding-top: 2%;padding-bottom:20px">
          <el-form ref="form" :model="gengangpractice" label-width="80px">
            <el-form-item style="width: 50%" label="群体设置">
              <span :key="item.id" v-for="item in row.student" style="margin-right: 10px">{{item.stuName}}</span>
            </el-form-item>
            <el-form-item style="padding-bottom: 2%">
              <el-button type="primary" @click="gengangpracticeSet">设置</el-button>
              <el-button @click="backTo">返回</el-button>
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
    name: 'Surveypublish',
    props: ['id', 'flag', 'row'],
    data () {
      return {
        pushOptions: [],
        gengangpractice: {
          content: '',
          push: []
        },
        activeName: 'first',
        classList: [],
        stuList: [],
        stuEmail: [],
        companyList: [],
        teacherList: [],
        postType: '',
        textarea: '',
        // qrcode:'',
        activeName2: 'first',
        dialogTableVisible: false,
        dialogTableVisible1: false,
        form: {
          post: '',
          content: '',
          link: '',
          date1: '',
          date2: '',
          delivery: false,
          address: [],
          type: [],
          resource: '',
          desc: ''
        },
        formLabelWidth: '120px'
      }
    },

    created () {
      console.log(this.row)
      this.getdata()
    },

    methods: {
      getdata: function () {
        this.form.post = '614490691@qq.com'
        this.form.content = '山西大同幼师学校有一份问卷请您填写，链接为：' + 'http://localhost:8081/requirement/surveyview?id=' + this.id
        this.form.link = 'http://localhost:8081/requirement/surveyview?id=' + this.id
        if (this.flag == 20) {
          let that = this
          this.$http.post('/gengangpractice/remind/getpush').then(function (res) {
            that.pushOptions = res.data.data
            console.log(res.data.data)
          })
          this.$http.post('/requirement/publish/getgengangpractice', { id: this.id }).then(function (res) {
            that.gengangpractice.content = res.data.data.content
            that.gengangpractice.push = res.data.data.push
            console.log(res.data.data)
          })
        }
      },

      getstudent: function (classId) {
        let that = this
        this.$http.post('/requirement/publish/getstudent', {
          classId: classId
        }).then(function (res) {
          that.stuEmail = res.data.data
          that.$http.post('/requirement/publish/getemail', {
            address: that.stuEmail,
            content: that.form.content
          }).then(function (res) {
            alert('邮件发送成功！')
            location.reload()
          })
        })
      },

      handleClick (tab, event) {
        console.log(tab, event)
      },
      onSubmit () {
        let that = this
        this.$http.post('/requirement/publish/sendemail', {
          student: this.row.student,
          content: this.form.content
        }).then(function (res) {
          console.log(res.data.data)
          alert('邮件发送成功！')
        })
      },
      share (shareType) {
        var shareUrl
        if (shareType == 1) {
          shareUrl = 'https://connect.qq.com/widget/shareqq/index.html?url=' + 'http://127.0.0.1/trainManage/trainApi/backend/web/index.php/requirement/surveyview?id=' + this.id + '&title=Hi，你好，这是我在智能问卷调查系统上创建的问卷，烦请填写！'
        } else if (shareType == 2) {
          shareUrl = 'http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url=' + encodeURIComponent('http://127.0.0.1/trainManage/trainApi/backend/web/index.php/requirement/surveyview?id=' + this.id) + '&site=智能问卷调查系统' + '&title=Hi，你好，这是我在智能问卷调查系统上创建的问卷，烦请填写！ '
        } else if (shareType == 3) {
          shareUrl = 'http://service.weibo.com/share/share.php?title=' + 'http://127.0.0.1/trainManage/trainApi/backend/web/index.php/requirement/surveyview?id=' + this.id + '&appkey=智能问卷调查系统'
        }
        window.open(shareUrl)
      },
      qrcode () {
        let qrcode = new QRCode('qrcode', {
          width: 100,
          height: 100, // 高度
          text: 'http://127.0.0.1/trainManage/trainApi/backend/web/index.php/requirement/surveyview?id=' + this.id // 二维码内容
        })
      },
      gengangpracticeSubmit () {
        console.log(this.gengangpractice,this.id)
        this.$http.post('/requirement/publish/submitgengangpractice2', { content: this.gengangpractice.content, id: this.id, pushId: this.row.id }).then(function (res) {
          alert('系统推送成功！')
        })
      },
      gengangpracticeSet () {
        this.$http.post('/requirement/publish/setgengangpractice2', { student: this.row.student, id: this.id }).then(function (res) {
          alert('设置成功！')
        })
      },
      visibleChange (val) {
        if (val == true) {
          this.gengangpractice.push = []
        }
        console.log(val)
      },

      backTo () {
        this.$emit('listenToPublish', 'back')
      }
    },
    mounted () {
      this.qrcode()
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
