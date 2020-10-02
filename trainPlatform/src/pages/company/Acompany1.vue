<template>
  <div class="display1">
    <button class="btn-header active">
      <span>企业信息</span>
    </button>
    <div class="display2">
      <div class="add-content">
        <div>
          <label> 企业名称</label>
          <p class="inputdiv" v-show="!isEdit"> {{companyList.comName}}</p>
          <input v-show="isEdit" class="inputdiv" type="text"  v-model="companyList.comName">
        </div>
        <div>
          <label>企业地址</label>
          <p class="inputdiv" v-show="!isEdit"> {{companyList.comAddress}}</p>
          <input v-show="isEdit" class="inputdiv" type="text"  v-model="companyList.comAddress">
        </div>
        <div>
          <label>企业电话</label>
          <p class="inputdiv" v-show="!isEdit"> {{companyList.comPhone}}</p>
          <input v-show="isEdit" class="inputdiv"  v-model="companyList.comPhone" >
        </div>
        <div>
          <label>企业邮箱</label>
          <p class="inputdiv" v-show="!isEdit"> {{companyList.comEmail}}</p>
          <input v-show="isEdit" class="inputdiv" v-model="companyList.comEmail">
        </div>

      </div>
      <div class="add-btn">
        <button  class="btn2 icon-huanyihuan" v-on:click="reset()">重置</button>
        <button v-show="!isEdit" class="btn1 icon-duigou" v-on:click="edit()">编辑</button>
        <button  v-show="isEdit" type="submit" class="btn1 icon-duigou" v-on:click="submit(companyList.id,companyList.comName,companyList.comAddress
        ,companyList.comPhone,companyList.comEmail)">确认
        </button>
      </div>
    </div>
  </div>

</template>

<script>
  var companyAccount;
  export default {
    name: 'Acompany1',
    data() {
      return {
        companyList:[],
        isEdit:false,
        power:this.$store.getters.showPower,

      }
    },
    methods: {
      edit(){
        this.isEdit=true;
      },
      geteditacompany:function(companyAccount){
        let that=this;
        this.$http.post('/company/company/geteditacompany',{companyAccount:companyAccount}).then(function (res) {
          that.companyList=res.data.data[0];
          console.log(res.data);
          console.log(that.companyList);
        })
      },
      reset:function(){
        this.companyList=[];
      },
      back(){
        this.$router.push({ path:"/company?name=企业管理" });
      },
      submit:function (companyId,a1,a3,a4,a5) {
        this.isEdit=false;
        if (a1!=''&&a3!=''&&a4!=''){
          this.$http.post('/company/company/altercompany', {
            companyId:companyId,
            companyName:a1,
            companyAddress:a3,
            companyPhone:a4,
            companyEmail:a5,
          }).then(function(res){
            console.log(res.data);
            if(res.data.message=="success"){
              this.geteditacompany(companyAccount);
              alert("企业信息修改成功！");
            }else{
              alert("企业信息修改失败！");
            }
          }).catch(function (error) {
            console.log(error);
          });
        }else if (a1==''||a3==''||a4==''){
          alert("企业信息不能为空！");
        }
      }
    },
    created(){
      companyAccount=this.power.username;
      console.log(companyAccount);
      this.geteditacompany(companyAccount);
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
