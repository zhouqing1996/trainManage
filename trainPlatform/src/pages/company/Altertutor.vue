<template>
<!--  //***未使用该文件-->
  <div>
    <el-dialog title="导师添加" :visible.sync="dvisible" @close='closeDialog'>
      <el-form :model="form">
        <el-form-item label="姓名" :label-width="formLabelWidth">
          <p class="inputdiv" v-show="!isEdit1"> {{classInfo.tutorName}}</p>
          <el-input v-model="form.name"></el-input>
        </el-form-item>
        <el-form-item label="身份证号" :label-width="formLabelWidth">
          <p class="inputdiv" v-show="!isEdit1"> {{classInfo.identity}}</p>
          <el-input type="textarea" v-model="form.identity"></el-input>
        </el-form-item>
        <el-form-item label="公司" :label-width="formLabelWidth">
          <p class="inputdiv" v-show="!isEdit1"> {{classInfo.companyAccount}}</p>
          <el-input type="textarea" v-model="form.company"></el-input>
        </el-form-item>
        <el-form-item label="性别" :label-width="formLabelWidth">
          <p class="inputdiv" v-show="!isEdit1"> {{classInfo.sex}}</p>
          <el-input type="textarea" v-model="form.sex"></el-input>
        </el-form-item>
        <el-form-item label="岗位" :label-width="formLabelWidth">
          <p class="inputdiv" v-show="!isEdit1"> {{classInfo.craft}</p>
          <el-input type="textarea" v-model="form.craft"></el-input>
        </el-form-item>
        <el-form-item label="电话" :label-width="formLabelWidth">
          <p class="inputdiv" v-show="!isEdit1"> {{classInfo.phone}}</p>
          <el-input type="textarea" v-model="form.phone"></el-input>
        </el-form-item>
        <el-form-item label="简介" :label-width="formLabelWidth">
          <p class="inputdiv" v-show="!isEdit1"> {{classInfo.profile}}</p>
          <el-input type="textarea" v-model="form.profile"></el-input>
        </el-form-item>
      </el-form>
      <div class="add-btn">
        <button  class="btn2 icon-huanyihuan" v-on:click="reset()">重置</button>
        <button type="submit" class="btn1 icon-duigou" v-on:click="submit(classInfo.tutorName,classInfo.identity,classInfo.sex,classInfo.craft,classInfo.phone,classInfo.profile)">确认
        </button>
      </div>
<!--      <div slot="footer" class="dialog-footer">-->
<!--        <el-button @click="visible(0)">取 消</el-button>-->
<!--        <el-button type="primary" @click="visible(1)">确 定</el-button>-->
<!--      </div>-->
    </el-dialog>
  </div>
</template>

<script>
    export default {
        name: "Altertutor",
        data() {
            return {
                classInfo:[],
                dvisible: false,
                form: {
                    name: '',
                    identity: '',
                    sex:'',
                    company:'',
                    craft:'',
                    phone: '',
                    profile:'',
                },
                formLabelWidth: '150px',
                isEdit:true,
                isEdit1:true,
            }
        },
        methods: {
            getedittutorroot:function(tutorId){
                let that=this;
                this.$http.post('/company/tutor/getedittutorroot',{tutorId:tutorId}).then(function (res) {
                    that.classInfo=res.data.data[0];
                    console.log(res);
                    console.log(that.classInfo);
                })
            },
            reset:function(){
                this.classInfo=[];
            },
            closeDialog () {
                this.$emit('child', 0)
            },
            // back(){
            //     this.$router.push({ path:"/company?name=企业管理" });
            // },
            submit:function (a,b,c,d,e,f,g) {
                let that=this;
                if (a!=''&&b!=''&&c!=''){
                    this.$http.post('/company/tutor/altertutorroot', {
                        tutorName:a,
                        identity:b,
                        sex:c,
                        company:d,
                        craft:e,
                        phone:f,
                        profile:g,
                    }).then(function(res){
                        console.log(res.data);
                        if(res.data.message=="success"){
                            alert("导师信息修改成功！");
                        }else{
                            alert("导师信息修改失败！");
                        }
                        that.getedittutorroot(id);
                        that.isE30000d
                        it1=false
                    }).catch(function (error) {
                        console.log(error);
                    });
                }else if (a==''||b==''||c==''){
                    alert("导师信息不能为空！");
                }
            }
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
