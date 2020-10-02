<template>
  <div class="display1">
    <button class="btn1">权限管理</button>
    <div class="display2">

      <div class="col-md-12">
        <div>
          <!--管理员添加-->
          <!--<div class="col-md-12">-->
            <!--<el-button class="btn5 icon-jiajianzujianjiahao" v-on:click="addPower"> 添加</el-button>-->
          <!--</div>-->
          <!--首页表格-->
          <table id="userStatistics">
            <tr>
              <th>序号</th>
              <th>姓名</th>
              <th>工号</th>
              <th> 权限类型</th>
              <th> 专业名称</th>
              <th> 专业管理</th>
              <th> 企业管理</th>
              <th> 学徒管理</th>
              <th> 实训管理</th>
              <th> 需求管理</th>
              <th> 追踪管理</th>
              <th> 权限管理</th>
              <th> 权限修改</th>
              <!--<th> 权限移交</th>-->
              <th>删除用户</th>
            </tr>
            <tr v-for="(user,key) in userPowerList" v-bind:key='user.id' v-if="teacherInfo[key].length!=0">
              <td>{{key+1}}</td>
              <td>{{teacherInfo[key][0]['teacherName']}}</td>
              <td>{{user.username}}</td>
              <td v-if='user.super==1'>超级管理员</td>
              <td v-if='user.super==2'>专业管理员</td>
              <td v-if='user.super==3'>班主任</td>
              <td>{{teacherInfo[key][0]['major']}}</td>
              <td><input type="checkbox" v-model="user.major==1" disabled="true"></td>
              <td><input type="checkbox" v-model="user.company==1" disabled="true"></td>
              <td><input type="checkbox" v-model="user.apprenticeship==1" disabled="true"></td>
              <td><input type="checkbox" v-model="user.practice==1" disabled="true"></td>
              <td><input type="checkbox" v-model="user.requirement==1" disabled="true"></td>
              <td><input type="checkbox" v-model="user.tracking==1" disabled="true"></td>
              <td><input type="checkbox" v-model="user.system==1" disabled="true"></td>
              <!--权限修改-->
              <td><button class="btn2 " v-on:click="modifyPower(user)"> 修改</button></td>
              <!--权限移交-->
              <!--<td><button class="btn2 " v-on:click="alterPower(user.syspowerId)"> 移交</button></td>-->
              <td>
                <span v-on:click="deleteuserpower(user.id)">删除</span>
              </td>
            </tr>
          </table>
          <!--//添加管理员-->
          <el-dialog title="修改权限" :visible.sync="dialogFormVisible">
            <el-form>
              <!--<input type="text" v-model="inputUser">-->
              <!--<input v-on:click="searchUserList" value="姓名检索" type="button">-->
              <!--//显示带设置成员列表-->
              <!--<div v-if="myVar==3">-->
                <!--<table>-->
                  <!--<thead>-->
                  <!--<tr>-->
                    <!--<th>序号</th>-->
                    <!--<th>工号</th>-->
                    <!--<th>专业</th>-->
                    <!--<th>姓名</th>-->
                    <!--<th>身份证</th>-->
                    <!--<th>性别</th>-->
                    <!--<th>职务</th>-->
                    <!--<th>职称</th>-->
                    <!--<th>电话</th>-->
                    <!--<th>操作</th>-->
                  <!--</tr>-->
                  <!--</thead>-->
                  <!--<tbody>-->
                  <!--<tr v-if="searchList.length==0">-->
                    <!--<td colspan="10">未查到非管理员用户</td>-->
                  <!--</tr>-->
                  <!--<tr v-for="(user,index) in searchList" :key="index">-->
                    <!--<td>{{index+1}}</td>-->
                    <!--<td>{{user.job_num}}</td>-->
                    <!--<td>{{user.major}}</td>-->
                    <!--<td>{{user.teacherName}}</td>-->
                    <!--<td>{{user.identity}}</td>-->
                    <!--<td>{{user.sex}}</td>-->
                    <!--<td>{{user.rank}}</td>-->
                    <!--<td>{{user.post}}</td>-->
                    <!--<td>{{user.contactPhone}}</td>-->
                    <!--<td v-on:click='setAdmin(user.job_num,user.teacherName,user.major)'>选择</td>-->
                  <!--</tr>-->
                  <!--</tbody>-->
                <!--</table>-->
              <!--</div>-->
                <!--//显示查找失败结果-->
                <div v-if="myVar==4">
                  <div>{{ searchResult }}</div>
                </div>
                <!--//显示权限添加-->
                <div v-if="myVar==5">
                  <div v-model="toadmin">
                    <span>用户ID：{{toadmin[0]}}</span>
                    <span>姓名：{{toadmin[1]}}</span>
                  </div>
                  <div>
                    <table>
                      <thead>
                      <tr>
                        <th>管理员</th>
                        <th>专业管理</th>
                        <th>公司管理</th>
                        <th>学徒管理</th>
                        <th>实训管理</th>
                        <th>需求管理</th>
                        <th>追踪管理</th>
                        <th>系统管理</th>
                      </tr>
                      <tr>
                        <th>
                          <select  v-model="form.super" style="font-size:12px;width:90px;" >
                            <option disabled value="">选择</option>
                            <option value="1">超级管理员</option>
                            <option value="2">专业管理员</option>
                            <option value="3">班主任</option>
                          </select>
                        </th>
                        <th><input type="checkbox" v-model="form.major"></th>
                        <th><input type="checkbox" v-model="form.company"></th>
                        <th><input type="checkbox" v-model="form.apprenticeship"></th>
                        <th><input type="checkbox" v-model="form.practice"></th>
                        <th><input type="checkbox" v-model="form.requirement"></th>
                        <th><input type="checkbox" v-model="form.tracking"></th>
                        <th><input type="checkbox" v-model="form.system"></th>
                      </tr>
                      </thead>
                    </table>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal" v-on:click="closeModal">取消</button>
                    <button type="button" class="btn btn-primary" v-on:click="saveAdmin()" data-dismiss="modal">确定</button>
                  </div>
                </div>
            </el-form>
          </el-dialog>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    name: "power",
    data() {
      return {
        form:{
          userId:"",
          username:"",
          majorId:"",
          super:"",
          major:false,
          company:false,
          practice:false,
          requirement:false,
          tracking:false,
          apprenticeship:false,
          system:false,
        },
        userPowerList: [],
        teacherInfo:[],
        searchList:[],
        //检索内容
        inputUser:"",
        searchResult:"", dialogFormVisible: false,
        myVar: 1,
        toadmin:[],
        oldPower:"",
        isEdit:false
      };
    },
    created: function () {
      this.getuserpowerInfo();
    },
    methods: {
      //所有查找权限的请求
      getuserpowerInfo:function(){
        let that=this;
        this.$http.get('/system/power/showpowerdata').then(function (res) {
          console.log(res.data);
          that.userPowerList=res.data.data[0];
          that.teacherInfo=res.data.data[1];
        })
      },
      // searchUserList: function () {
      //   let that=this;
      //   that.$http.post('/system/power/get-member-no-admin',{
      //     inputUser:that.inputUser
      //   }).then(body => {
      //     console.log(body.data);
      //     this.searchList = body.data.data;
      //     this.myVar=3;
      //   })
      // },
      modifyPower: function (user) {
        this.dialogFormVisible = true;
        this.myVar=5;
        this.toadmin=[];
        this.toadmin.push(user.syspowerId);
        this.toadmin.push(user.username);
        this.isEdit=true;
        this.form.userId=user.syspowerId;
        this.form.majorId=user.majorId;
        this.form.username=user.username;
        this.form.major=user.major==="1";
        this.form.company=user.company==="1";
        this.form.practice=user.practice==="1";
        this.form.requirement=user.requirement==="1";
        this.form.tracking=user.tracking==="1";
        this.form.apprenticeship=user.apprenticeship==="1";
        this.form.system=user.system==="1";
        this.form.super=user.super;
        this.form.userkind=user.userkind;
        console.log(this.form)
      },
      addPower() {
        this.dialogFormVisible = true;
        this.reset();
      },
      alterPower: function (userId) {
        this.dialogFormVisible = true;
        this.myVar=3;
        this.oldPower=userId;
      },

      //选择要设置为管理员的成员
      setAdmin:function (user,name,majorId) {
        console.log(user);
        if(this.oldPower!=""){
          this.alterAdmin(user,majorId);
          //交换权限
        }else{
          console.log(user);
          this.myVar=5;
          this.form.username=user;
          this.form.majorId=majorId;
          this.toadmin=[];
          this.toadmin.push(user);
          this.toadmin.push(name);
          console.log(this.form);
        }
      },
      reset(){
        this.myVar=3;
        this.toadmin=[];
        this.oldPower="";
        this.isEdit=false;
        this.form.company=false;
        this.form.major=false;
        this.form.practice=false;
        this.form.requirement=false;
        this.form.tracking=false;
        this.form.apprenticeship=false;
        this.form.system=false;
        this.form.super="";
        this.form.username="";
        this.form.userId="";
        this.form.majorId="";
        this.searchList=[];
        this.inputUser="";
      },
      //关闭弹窗
      closeModal:function () {
        this.reset();
      },
      alterAdmin(user,majorId){
        let data={
          USER:this.oldPower,
          USERID: user,
          majorId:majorId,

        }
        this.$http.post('/system/power/movepower',data)
          .then((res) => {
            console.log(res.data);
            if(res.data.data!=null){
              alert(res.data.message);
              this.dialogFormVisible = false;
              this.reset();
              this.getuserpowerInfo();
            }else{
              alert(res.data.message);
            }
          }, (err) => {
            console.log(err)
          })
      },
      //保存管理员的设置操作
      saveAdmin:function () {
        let data=this.form;
        console.log(data)
        let url="";
        let flag=0;
        if(!this.isEdit){
          url='/system/power/addpowerdata';
        }else{
          url='/system/power/updatepower';
          flag=1;
        }
        this.$http.post(url,data).then((res) => {
          console.log(res.data);
          if(res.data.message==1){
              this.dialogFormVisible = false;
              this.reset();
              this.getuserpowerInfo();
              if (flag==0){
                alert("添加成功！");
              } else{
                alert("修改成功！");
              }
            }else{
              alert(res.data.message);
            }
          })
      },
      //删除管理员
      deleteuserpower: function (a) {
        let data={userId:a};
        this.$http.post('/system/power/deletepowerdata',data)
          .then((res) => {
            if (res.data.data != null) {
              alert("删除成功");
              this.getuserpowerInfo();
            }else{
              alert("删除失败")
            }
        }).catch(function (error) {
            console.log(error);
        });
      },
    },
  };
</script>

<style scoped>
  .display1 {
    /*  padding-left: 20px;*/
    padding-top: 10px;
    padding-left: 10px;
  }

  .display2 {
    border: solid 1px #e0e0e0;
    height: 600px;
    /*text-align: center;*/
    width: 98%;
    padding-left: 10px;
    padding-right: 10px;
    background-color: #fff;
  }

  .user {
    float: left;
    margin: 10px 0 10px 0;
    font-weight: bold;
    background-color: #00aaff;
    border: solid 1px #00aaff;
    border-radius: 5px;
    width: 40%;
    padding: 5px;
  }

  .sort {
    color: #1c93fc;
  }

  .sort:hover {
    color: #5cb0fa;
  }

  table {
    border-collapse: collapse;
    width: 100%;
    margin-top: 10px;
  }

  th {
    font-size: 14px;
    border: solid 1px #ccc;
    font-weight: bold;
    padding: 5px;
    background-color: #f1f1f1;
  }

  table,
  td {
    border: solid 1px #ccc;
    padding: 5px;
    text-align: center;
    font-size: 14px;
  }

  .btn1 {
    font-size: 18px;
    padding: 10px 10px;
    border-top-left-radius: 3px;
    border-top-right-radius: 3px;
    border: 1px solid #ccc;
    cursor: pointer;
    background-color: #fff;
    margin-bottom: -1px;
    color: #000;
    /*margin-right: 0px;*/
  }

  .btn1:hover {
    background: #e0e0e0;
  }

  /*.active {*/
    /*background: #e0e0e0;*/
  /*}*/

  .btn2 {
    margin-left: 0px;
    background-color: #338ffc;
    color: white;
    border: none;
    border-radius: 3px;

  }

  .input1 {
    width: 80px;
  }

  .btn3 {
    width: 80px;
    padding: 7px;
    font-size: 14px;
    border-radius: 3px;
    border: none;
    color: white;
    background-color: #338ffc;
    float: left;
    margin-left: 15px;
    margin-top: 13px;
    /*margin-bottom: 5px;*/
  }

  .btn3:hover {
    background-color: #5fa7fe;
  }

  .btn4 {
    width: 90px;
    padding: 7px;
    font-size: 14px;
    border-radius: 3px;
    border: none;
    color: white;
    background-color: #fa4e28;
    float: right;
    /*margin-left: 15px;*/
    margin-top: 13px;
    /*margin-bottom: 5px;*/
  }

  .btn4:hover {
    background-color: #fc6f4f;
  }

  .btn5 {
    width: 66px;
    padding: 7px;
    font-size: 14px;
    border-radius: 3px;
    border: none;
    color: white;
    background-color: #338ffc;
    float: right;
    margin-right: 0px;
    margin-top: 13px;
    margin-bottom: 5px;
  }

  .delete:hover {
    color: #c1c1c2;
  }

  .page {
    text-align: center;
  }

  .top {
    background: #e0e0e0;
  }

  .el-date-editor--datetimerange.el-input,
  .el-date-editor--datetimerange.el-input__inner {
    width: 360px !important;
  }
</style>
