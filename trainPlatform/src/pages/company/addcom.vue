<template>
  <div class="display1">
    <button class="btn-header active">
      <span v-show="isEdit">信息编辑</span>
      <span v-show="!isEdit">企业添加</span>
      <span style="font-size: 13px" @click="back()">
        （返回列表）
      </span>
    </button>
    <router-link to="/company/company"></router-link>
    <div class="display2">
      <div class="add-content">
        <div>
          <label> 企业名称</label>
          <input class="inputdiv" type="text" placeholder="请输入企业名称" v-model="addname">
        </div>
        <div>
          <label>企业账户</label>
          <input class="inputdiv" type="text" placeholder="请输入企业用户" v-model="addaccount">
        </div>
        <div>
          <label>企业地址</label>
          <input class="inputdiv" type="text" placeholder="请输入企业地址" v-model="addaddress">
        </div>
        <div>
          <label>企业电话</label>
          <input class="inputdiv" placeholder="请输入企业电话" v-model="addphone" >
        </div>
        <div>
          <label>企业邮箱</label>
          <input class="inputdiv" type="text" placeholder="请输入企业邮箱" v-model="addemail">
        </div>

      </div>
      <div class="add-btn">
        <button  class="btn2 icon-huanyihuan" v-on:click="reset()">重置</button>
        <button type="submit" class="btn1 icon-duigou" v-on:click="submit(addname,addaccount,addaddress,addphone,addemail)">确认
        </button>
      </div>

    </div>
  </div>

</template>

<script>
  import { common } from '../../main'

  export default {
    name: 'addcom',
    props: ["data"],
    data() {
      return {
        isEdit:'',
        addname:'',
        addaccount:'',
        addaddress:'',
        addphone:'',
        addemail:'',
        addpassword:'',
      }
    },
    methods: {
      reset:function(){
        this.addname='';
        this.addaccount='';
        this.addaddress='';
        this.addphone='';
        this.addemail='';
        this.addpassword='';
      },
      back(){
        this.$router.push({ path:"/company?name=企业管理" });
      },
      submit:function (a1,a2,a3,a4,a5) {
        if (a1!=''&&a2!=''&&a3!=''&&a4!=''){
          this.$http.post('/company/company/insertcompany', {
            companyName:a1,
            companyAccount:a2,
            companyAddress:a3,
            companyPhone:a4,
            companyEmail:a5,
            // maojorManager:maojorManager,
          }).then((res)=>{
            console.log(res)
            // if(res.data.message==1){
            //   common.$emit('showResList',"企业信息添加成功！");
            //   this.$router.push({path:"/company?name=企业管理"});
            // }else{
            //   alert("企业信息添加失败！");
            // }
          });
        }else if(a1==''||a2==''||a3==''||a4=='') {
          alert("企业信息不能为空！");
        }
      }

    },
    watch: {
      data: {
        handler(newValue, oldValue) {
          console.log(newValue);
          console.log(oldValue);
          if (newValue!="add") {
            this.isEdit=true,
              this.addname=this.data.comName;
            this.addaccount=this.data.comAccount;
            this.addaddress=this.data.comAddress;
            this.addphone=this.data.comPhone;
            this.addemail=this.data.comEmail;
            this.addpassword=this.data.comPassword;

          }else{
            this.isEdit=false,
              this.addname="";
            this.addaccount="";
            this.addaddress="";
            this.addphone="";
            this.addemail="";
            this.addpassword="";
          }
        },
        deep: true//未知变量
      }
    }
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
