<template>
  <div style="width: 80%;margin:auto;">
    <el-tabs v-model="activeName" type="card" @tab-click="handleClick">
      <el-tab-pane name="first">
        <span slot="label"><i class="el-icon-message"></i> 需求调查推送</span>
        <div style="width: 100%;background-color: white;padding-top: 2%">
          <el-form ref="form" :model="form" label-width="80px">
            <el-form-item style="width: 50%" label="发送群体">
              <el-button @click="getcompany()" type="text">企业用户</el-button>
              <!--<el-button @click="getteacher()" type="text">教师用户</el-button>-->
              <!--<el-button @click="getclass()" type="text">学生用户</el-button>-->
            </el-form-item>
            <el-form-item label="企业名称">
              <el-checkbox-group v-model="form.address">
                <el-checkbox v-for="(item,key) in companyList" :key="item" :label="item.comAccount" name="address">
                  {{item.comName}}</el-checkbox>
              </el-checkbox-group>
            </el-form-item>

            <el-form-item style="padding-bottom: 2%">
              <el-button type="primary" @click="onSubmit">发送需求调查</el-button>
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
  export default {
    name: "Demandpublish",
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
      this.getcompany();
    },

    methods: {
      getdata:function(){
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


      getcompany:function(){
        let that=this;
        this.$http.post('/requirement/publish/getcompany').then(function (res) {
          console.log(res.data);
          that.companyList = res.data.data;
          that.postType = 'company' ;
        })
      },

      handleClick(tab, event) {
        console.log(tab, event);
      },
      onSubmit() {
        console.log(this.form)
        this.$http.post('/requirement/publish/demandpush',{
          address:this.form.address,
          content:this.form.content,
          surveyId:this.id
        }).then(function (res) {
          console.log(res)
          alert("需求调查推送成功！");
          // location.reload();
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
