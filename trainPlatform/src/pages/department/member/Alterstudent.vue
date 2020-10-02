<template>
  <div class="display1">
    <button class="btn-header active">
      <span v-show="isEdit">信息编辑</span>
      <span v-show="!isEdit">学生添加</span>
      <span style="font-size: 13px" @click="back()">
        （返回列表）
      </span>
    </button>
    <router-link to="/department/member"></router-link>
    <div class="display2">
      <div class="add-content">
        <div>
          <label>姓&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp名</label>
          <input class="inputdiv" type="text" placeholder="请输入姓名" v-model="studentList.stuName">
        </div>
        <div>
          <label>身份证</label>
          <input class="inputdiv" type="text" placeholder="请输入身份证" v-model="studentList.identity">
        </div>
        <div>
          <label>性别</label>
          <select class="inputdiv" v-model="studentList.sex" style="font-size:15px;width:180px;" >
            <option disabled value="">选择</option>
            <option value="1">男</option>
            <option value="2">女</option>
          </select>
        </div>
        <div>
          <label>出生日期</label>
          <input class="inputdiv" type="text"  placeholder="请输入出生日期" v-model="studentList.bornDate" >
        </div>
        <div>
          <label>班&nbsp&nbsp&nbsp级</label>
          <select class="inputdiv" v-model="studentList.classId" style="font-size:15px;width:180px;" >
            <option disabled value="">选择</option>
            <option v-bind:value="Item.id" v-for="Item in classList" >{{Item.grade}}-{{Item.major}}专业{{Item.className}}</option>
          </select>
        </div>
        <div>
          <label>电话</label>
          <input class="inputdiv" type="text"  placeholder="请输入联系电话" v-model="studentList.contactPhone" >
        </div>
        <div>
          <label>邮&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp箱</label>
          <input class="inputdiv" type="text"  placeholder="请输入邮箱" v-model="studentList.email" >
        </div>
      </div>
      <div class="add-btn">
        <button  class="btn2 icon-huanyihuan" v-on:click="reset()">重置</button>
        <button type="submit" class="btn1 icon-duigou" v-on:click="submit()">确认
        </button>
      </div>

    </div>
  </div>

</template>

<script>
  import { common4 } from '../../../main'
  var studentId;
  export default {
    name: 'alterstudent',
    props: ["data"],
    data() {
      return {
        classList:[],
        studentList:[],
        inputpassword:"123456",
        isEdit:true,

      }
    },
    methods: {
      getClassData:function(){
        let that=this;
        this.$http.get('/department/member/getclassdata').then(function (res) {
          that.classList=res.data.data;
        })
      },
      geteditstudent:function(studentId){
        let that=this;
        this.$http.post('/department/member/geteditstudent',{studentId:studentId}).then(function (res) {
          that.studentList=res.data.data[0];
        })
      },
      reset:function(){
        this.studentList=[];
        this.inputpassword="";

      },

      back(){
        this.$router.push({ path:"/department/member/member?name=学生信息" });
      },

      submit:function () {
        let that = this;
        if (that.studentList.stuName && that.studentList.identity){
          this.$http.post('/department/member/alterstudent', {
            studentId:studentId,
            studentName:that.studentList.stuName,
            studentIdentify:that.studentList.identity,
            studentSex:that.studentList.sex,
            studentBorndate:that.studentList.bornDate,
            studentClass:that.studentList.classId,
            studentPhone:that.studentList.contactPhone,
            studentEmail:that.studentList.email,
          }).then((res)=>{
            if(res.data.data == 1){
              alert("学生信息编辑成功！");
              common4.$emit('showResList',1);
              that.$router.push({ path:"/department/member/member?name=学生信息"});
            }else{
              alert("学生信息编辑失败！");
            }
          });
        }else if(a1==''||a2==''||a3==''||a4==''||a5==''||a6==''||a8=='') {
          alert("学生信息不能为空！");
        }
      }

    },
    created() {
      this.getClassData();
      studentId=this.$route.query.studentId;
      this.geteditstudent(studentId);
    },

  }
</script>

<style scoped>
  label input select {
    float: left;

  }

  .add-content {
    padding: 30px;
    display: flex;
    flex-wrap: wrap;
    align-content: flex-start
  }

  .add-content > div {
    width: 30%;
  }

  .inputdiv {
    margin-bottom: 25px;

  }

  .display1 {
    padding-left: 5px;
    padding-top: 10px;

  }

  .display2 {
    border: solid 2px #e0e0e0;
    height: auto;
    width: 90%;
    padding: 100px 50px;
    background-color: #fff;
  }

  .btn-header {
    font-size: 18px;
    padding: 10px 10px;
    border-top-left-radius: 3px;
    border-top-right-radius: 3px;
    border: 1px solid #ccc;
    cursor: pointer;
    background: #f0f0f0;

    color: #000;
    width: auto;
    /*margin-right: 0px;*/
  }

  .btn-header:hover {
    background: #e0e0e0;
  }

  .btn {
    margin-right: 750px;
    float:right;
  }
  .btn1{
    width:80px;
    padding:7px;
    font-size: 16px;
    border-radius: 3px;
    border:none;
    color:white;
    background-color:#338FFC ;
    margin-left: 20px;
    margin-top: 0px;
    /*margin-bottom: 5px;*/
  }
  .btn1:hover{
    background-color:#5FA7FE;
  }

  .active {
    background: #e0e0e0;
  }
  .btn2{
    width:80px;
    padding:6px;
    font-size: 16px;
    border-radius: 3px;
    border:none;
    color:white;
    background-color:#898989;
    float: left;
    margin-left: 10px;


  }
  .btn2:hover{
    background-color:#A5A5A5;
  }

</style>
