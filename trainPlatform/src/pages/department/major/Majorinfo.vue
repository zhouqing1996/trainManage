<template>
  <div class="display1">
    <button class="btn-header active">
      <span>专业信息</span>
    </button>
    <div class="display2">
      <div class="add-content">
        <div>
          <label> 专业名称</label>
          <p class="inputdiv"> {{majorList.major}}</p>
          <input v-show="isEdit" class="inputdiv" type="text"  v-model="majorList.major">
        </div>
        <div>
          <label>专业简介</label>
          <p class="inputdiv"> {{majorList.profile}}</p>
          <input v-show="isEdit" class="inputdiv" type="text"  v-model="majorList.profile">
        </div>
        <!--<div>-->
          <!--<label>管理员</label>-->
          <!--<p class="inputdiv" v-show="!isEdit"> {{majorList.teacherName}}</p>-->
          <!--<input v-show="isEdit" class="inputdiv" type="text"  v-model="majorList.teacherName">-->
        <!--</div>-->
      </div>
      <div class="add-btn">
        <button  class="btn2 icon-huanyihuan" v-on:click="reset()">重置</button>
        <button v-show="!isEdit" class="btn1 icon-duigou" v-on:click="edit()">编辑</button>
        <button  v-show="isEdit" type="submit" class="btn1 icon-duigou" v-on:click="submit(majorList.id,majorList.major,majorList.profile,
        majorList.teacherName)">确认
        </button>
      </div>

    </div>
  </div>

</template>

<script>
  var majorName;
  export default {
    name: 'Majorinfo',
    data() {
      return {
        majorId:'',
        majorList:[],
        teacherInfo:[],
        isEdit:false,
        power:this.$store.getters.showPower,

      }
    },
    methods: {
      edit(){
        this.isEdit=true;
      },
      geteditmajor:function(){
        let that=this;
        this.$http.post('/department/major/geteditamajor',{majorId:that.majorId}).then(function (res) {
          that.majorList=res.data.data;
          console.log(that.majorList);
          /* that.teacherId = that.majorInfo.teacherName;
           console.log(that.teacherId,that.majorInfo);*/
        })
      },
      reset:function(){
        this.majorList=[];
      },

      submit:function (a1,a2) {
        let that = this;
        console.log(that.majorId)
        console.log(a1)
        console.log(a2)
        if (a1!=''&&a2!=''){
          this.$http.post('/department/major/alteramajor', {
            majorId:that.majorId,
            majorName:a1,
            majorProfile:a2,
          }).then(function(res){
            console.log(res.data);
            if(res.data.data!=0){
              alert("专业信息修改成功！");
            }else{
              alert("专业信息修改失败！");
            }
            that.geteditmajor(that.power.username);
            that.isEdit=false;
          }).catch(function (error) {
            console.log(error);
          });
        }else if (a1==''||a2==''){
          alert("专业信息不能为空！");
        }
      }
    },
    created(){
      console.log(this.power);
      majorName=this.power.username;
      this.majorId=this.power.majorId;
      this.geteditmajor();
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
