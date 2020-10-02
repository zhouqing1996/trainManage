<template>
  <div class="display1">
    <button class="btn-header active">
      <span v-show="isEdit">信息编辑</span>
      <span v-show="!isEdit">教师添加</span>
      <span style="font-size: 13px" @click="back()">
        （返回列表）
      </span>
    </button>
    <router-link to="/department/member"></router-link>
    <div class="display2">
      <div class="add-content">
        <div>
          <label>姓&nbsp名</label>
          <input class="inputdiv" type="text" placeholder="请输入姓名" v-model="teacherList.teacherName">
        </div>
        <div>
          <label>身份证</label>
          <input class="inputdiv" type="text" placeholder="请输入身份证" v-model="teacherList.identity">
        </div>
        <!--<div>-->
          <!--<label>工号</label>-->
          <!--<input class="inputdiv" type="text" placeholder="请输入工号" v-model="teacherList.job_num">-->
        <!--</div>-->
        <div>
          <label>专&nbsp&nbsp&nbsp业</label>
          <select class="inputdiv" v-model="teacherList.majorId" style="font-size:15px;width:180px;" >
            <option disabled value="">选择</option>
            <option v-bind:value="Item.id" v-for="Item in majorList" >{{Item.major}}</option>
          </select>
        </div>
        <div>
          <label>性&nbsp&nbsp&nbsp别</label>
          <select class="inputdiv" v-model="teacherList.sex" style="font-size:15px;width:180px;" >
            <option disabled value="">选择</option>
            <option value="1">男</option>
            <option value="2">女</option>
          </select>
        </div>
        <div>
          <label>职&nbsp称</label>
          <input class="inputdiv" type="text"  placeholder="请输入职称" v-model="teacherList.rank" >
        </div>
        <div>
          <label>职&nbsp&nbsp&nbsp务</label>
          <input class="inputdiv" type="text"  placeholder="请输入职务" v-model="teacherList.post" >
        </div>
        <div>
          <label>电&nbsp&nbsp&nbsp话</label>
          <input class="inputdiv" type="text"  placeholder="请输入联系电话" v-model="teacherList.contactPhone" >
        </div>
        <div>
          <label>邮&nbsp箱</label>
          <input class="inputdiv" type="text"  placeholder="请输入邮箱" v-model="teacherList.email" >
        </div>
      </div>
      <div class="add-btn">
        <button  class="btn2 icon-huanyihuan" v-on:click="reset()">重置</button>
        <button type="submit" class="btn1 icon-duigou" v-on:click="submit()">确认</button>
      </div>
    </div>
  </div>
</template>

<script>
  import { common3 } from '../../../main'
  export default {
    name: 'addteacher',
    props: ["data"],
    data() {
      return {
        majorList: [],
        teacherList:[],
        isEdit:true,
      }
    },
    methods: {
      getMajorData:function(){
        let that=this;
        this.$http.get('/department/member/getmajordata?page=1').then(function (res) {
          that.majorList=res.data.data[0];
        })
      },

      geteditteacher:function(id){
        let that=this;
        this.$http.post('/department/member/geteditteacher',{id:id}).then(function (res) {
          that.teacherList=res.data.data;
        })
      },

      reset:function(){
        this.teacherList=[];
      },

      back(){
        this.$router.push({ path:"/department/member/member?name=教师信息" });
      },

      submit:function () {
        let that = this;
        if (that.teacherList.teacherName!= '' && that.teacherList.identity!= ''&& that.teacherList.majorId != '' && that.teacherList.contactPhone && that.teacherList.email!= ''){
          this.$http.post('/department/member/alterteacher', {
            teacherId:that.id,
            teacherName:that.teacherList.teacherName,
            teacherIdentify:that.teacherList.identity,
            teacherSex:that.teacherList.sex,
            teacherRank:that.teacherList.rank,
            teacherPost:that.teacherList.post,
            teacherPhone:that.teacherList.contactPhone,
            teacherEmail:that. teacherList.email,
            teacherMajor:that.teacherList.majorId,
          }).then((res)=>{
            if(res.data.data == 1){
              // alert("教师信息编辑成功！");
              if (that.flag == 1){
                common3.$emit('showResList',1);
              } else{
                common3.$emit('showResList',1);
              }
              that.$router.push({ path:"/department/member/member?name=教师信息"});
            }else{
              alert("教师信息编辑失败！");
            }
          });
        }else{
          alert("编辑信息不能为空！");
        }
      }
    },
    created() {
      this.id=this.$route.query.id;
      this.flag=this.$route.query.flag;
      this.geteditteacher(this.id);
      this.getMajorData();
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
