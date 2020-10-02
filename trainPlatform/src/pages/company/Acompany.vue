<template>
  <div class="display1">
    <button class="btn1" v-on:click="major1" v-bind:class="{ active: isActiveOne }">企业信息</button>
    <button class="btn1" v-on:click="class1" v-bind:class="{ active: isActiveTwo }">导师信息</button>
    <div class="display2" v-show="major">
      <div class="add-content">
        <!--从这里开始-->
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
        <div class="add-btn">
        <button class="btn3" type="text" @click="edit1()">编辑</button>
        <el-dialog title="编辑企业信息" :visible.sync="dialogFormVisible">
          <el-form :model="companyList">
            <el-form-item label="企业名称" :label-width="formLabelWidth">
              <el-input style="width: 350px;" v-model="companyList.comName" auto-complete="off"></el-input>
            </el-form-item>
            <el-form-item label="企业地址" :label-width="formLabelWidth">
            <el-input style="width: 350px;" v-model="companyList.comAddress" auto-complete="off"></el-input>
          </el-form-item>
            <el-form-item label="企业电话" :label-width="formLabelWidth">
              <el-input style="width: 350px;" v-model="companyList.comPhone" auto-complete="off"></el-input>
            </el-form-item>
            <el-form-item label="企业邮箱" :label-width="formLabelWidth">
              <el-input style="width: 350px;" v-model="companyList.comEmail" auto-complete="off"></el-input>
            </el-form-item>
          </el-form>
          <div slot="footer" style="align-content: center" class="dialog-footer">
            <el-button class="el-button" @click="reset()">重置</el-button>
            <el-button  class="el-button" @click="submit(companyList.id,companyList.comName,companyList.comAddress
        ,companyList.comPhone,companyList.comEmail)">确认</el-button>
          </div>
        </el-dialog>
        </div>
      </div>
    </div>
      <!--<div class="add-btn">
        <button  class="btn2 icon-huanyihuan" v-on:click="reset()">重置</button>
        <button v-show="!isEdit" class="btn1 icon-duigou" v-on:click="edit()">编辑</button>
        <button  v-show="isEdit" type="submit" class="btn1 icon-duigou" v-on:click="submit(companyList.id,companyList.comName,companyList.comAddress
        ,companyList.comPhone,companyList.comEmail)">确认
        </button>
      </div>-->
    <div class="display2" v-show="classes">
      <div class="searchmem">
        <button class="btn3" type="text" @click="dialogFormVisible1 = true;classform.tutorName='';classform.identity='';classform.craft='';classform.sex='';classform.phone='';classform.profile='';">添加</button>
        <el-dialog title="添加导师信息" :visible.sync="dialogFormVisible1">
          <el-form :model="classform">
            <el-form-item label="姓名" :label-width="formLabelWidth">
              <el-input style="width: 350px;" v-model="classform.tutorName" auto-complete="off"></el-input>
            </el-form-item>
            <el-form-item label="身份证号" :label-width="formLabelWidth">
              <el-input style="width: 350px;" v-model="classform.identity" auto-complete="off"></el-input>
            </el-form-item>
            <el-form-item label="岗位" :label-width="formLabelWidth">
              <el-input style="width: 350px;" v-model="classform.craft" auto-complete="off"></el-input>
            </el-form-item>
            <el-form-item label="性别" :label-width="formLabelWidth">
              <select v-model="classform.sex" style="font-size:15px;width:180px;" >
                <option disabled value="">选择</option>
                <option value="1">男</option>
                <option value="2">女</option>
              </select>
            </el-form-item>
            <el-form-item label="电话" :label-width="formLabelWidth">
              <el-input style="width: 350px;" v-model="classform.phone" auto-complete="off"></el-input>
            </el-form-item>
            <el-form-item label="简介" :label-width="formLabelWidth">
              <el-input style="width: 350px;" v-model="classform.profile" auto-complete="off"></el-input>
            </el-form-item>
          </el-form>
          <div slot="footer" style="align-content: center" class="dialog-footer">
            <el-button type="primary" @click="inserttutor(classform)">提交</el-button>
            <!--<el-button @click="resetclass()">重置</el-button>-->
          </div>
        </el-dialog>
        <el-dialog title="修改导师信息" :visible.sync="dialogFormVisible2">
          <el-form :model="classform">
            <el-form-item label="姓名" :label-width="formLabelWidth">
              <el-input style="width: 350px;" v-model="classform.tutorName" auto-complete="off"></el-input>
            </el-form-item>
            <el-form-item label="身份证号" :label-width="formLabelWidth">
              <el-input style="width: 350px;" v-model="classform.identity" auto-complete="off"></el-input>
            </el-form-item>
            <el-form-item label="岗位" :label-width="formLabelWidth">
              <el-input style="width: 350px;" v-model="classform.craft" auto-complete="off"></el-input>
            </el-form-item>
            <el-form-item label="性别" :label-width="formLabelWidth">
              <!--<el-input style="width: 350px;" v-model="classform.sex" auto-complete="off"></el-input>-->
              <select v-model="classform.sex" style="font-size:15px;width:180px;" >
                <option disabled value="">选择</option>
                <option value="1">男</option>
                <option value="2">女</option>
              </select>
            </el-form-item>
            <el-form-item label="电话" :label-width="formLabelWidth">
              <el-input style="width: 350px;" v-model="classform.phone" auto-complete="off"></el-input>
            </el-form-item>
            <el-form-item label="简介" :label-width="formLabelWidth">
              <el-input style="width: 350px;" v-model="classform.profile" auto-complete="off"></el-input>
            </el-form-item>
          </el-form>
          <div slot="footer" style="align-content: center" class="dialog-footer">
            <el-button type="primary" @click="updatetutor(classform)">提交</el-button>
          </div>
        </el-dialog>
      </div>
      <table id="studentStastics">
        <tr>
          <th>序号</th>
          <th>姓名 </th>
          <th>身份证号 </th>
          <th>岗位</th>
          <th>性别</th>
          <th>电话</th>
          <th>简介</th>
          <th>编辑</th>
          <th>删除</th>
        </tr>
        <tr v-for="(class1,index) in classInfo">
          <td>{{index+1}}</td>
          <td>{{class1.tutorName}}</td>
          <td>{{class1.identity}}</td>
          <td>{{class1.craft}}</td>
          <td v-if='class1.sex==1'>男</td>
          <td v-if='class1.sex==2'>女</td>
          <td v-if='class1.sex==""'>男</td>
          <td>{{class1.phone}}</td>
          <td>{{class1.profile}}</td>
          <td>
            <span @click="getedittutor(class1.id)">编辑</span>
          </td>
          <td>
            <span @click="deletetutor(class1.id)">删除</span>
          </td>
        </tr>
      </table>
    </div>
  </div>

</template>

<script>
  import { common } from '../../main'
  var companyAccount;
  export default {
    name: 'Acompany1',
    data() {
      return {
        companyList:[],
        isEdit:false,
        power:this.$store.getters.showPower,
        isActiveOne: true,
        isActiveTwo: false,
        majorId:'',
        major:true,
        classes:false,
        majorInfo:[],
        classInfo:[],
        inputClass:'',
        manager:'',
        majorName:'',
        isActiveOne: true,
        isActiveTwo: false,
        dialogFormVisible: false,
        dialogFormVisible1: false,
        dialogFormVisible2: false,
        form: {
          majorName: '',
          majorProfile: '',
        },
        classform: {
          tutorName:'',
          identity:'',
          craft:'',
          sex:'',
          phone:'',
          profile:''
        },
        formLabelWidth: '200px'
      }
    },
    methods: {
        edit1:function(){
            this.geteditacompany(companyAccount);
            this.dialogFormVisible = true;
            this.companyList.comName='';
            this.companyList.comAddress='';
            this.companyList.comPhone='';
            this.companyList.comEmail='';
        },

      major1: function () {
        this.major = true;
        this.classes = false;
        this.isActiveOne= true;
        this.isActiveTwo= false;
      },
      class1: function () {
        console.log(companyAccount)
        let that = this;
        this.$http.post('/company/tutor/getdata',{companyAccount:companyAccount}).then(function (res) {
          console.log(res.data)
          that.classInfo = res.data.data;
        })

        this.major = false;
        this.classes = true;
        this.isActiveOne= false;
        this.isActiveTwo= true;
      },
      edit(){
        this.isEdit=true;
      },
      geteditacompany:function(b){
        let that=this;
        this.$http.post('/company/company/geteditacompany',{companyAccount: b}).then(function (res) {
          that.companyList=res.data.data[0];
          console.log(res.data);
          console.log(that.companyList);
        })
      },
      reset:function(){
          this.companyList.comName='';
          this.companyList.comAddress='';
          this.companyList.comPhone='';
          this.companyList.comEmail='';
      },
      back(){
        this.$router.push({ path:"/company?name=企业管理" });
      },
      submit:function (companyId,a1,a3,a4,a5) {
        this.isEdit=false;
        let that=this;
        if (a1!=''&&a3!=''&&a4!=''){
          this.$http.post('/company/company/altercompany', {
            companyId:companyId,
            companyName:a1,
            companyAddress:a3,
            companyPhone:a4,
            companyEmail:a5,
          }).then(function(res){
            console.log(res.data)
            if(res.data.message=="success"){
                alert("企业信息修改成功！");
                that.dialogFormVisible = false
            }
            else{
              alert("企业信息修改失败！");
            }
          }).catch(function (error) {
            console.log(error);
          });
        }else if (a1==''||a3==''||a4==''){
          alert("企业信息不能为空！");
            this.dialogFormVisible=false;
        }
      },
      inserttutor:function(classform){
        console.log(classform)
        let that =this;
        if (classform.tutorName != '' && classform.identity != '' && classform.craft != ''){
          that.$http.post('/company/tutor/inserttutor', {
            name:classform.tutorName,
            identity:classform.identity,
            craft:classform.craft,
            companyAccount:companyAccount,
            sex:classform.sex,
            phone:classform.phone,
            profile:classform.profile
          }).then((res)=>{
            console.log(res)
            if(res.data.message == "添加成功"){
              // alert("导师信息添加成功")
              common.$emit('showClass',"导师信息添加成功！");
              // that.classform.className='';
              // that.dialogFormVisible1 = false;
            }else{
              alert("导师信息添加失败！");
            }
          });
        }else{
          alert("姓名、身份证号、工种不能为空！");
        }
      },
      getedittutor:function(tutorId){
        console.log(tutorId)
        let that=this;
        this.$http.post('/company/tutor/getedittutor',{tutorId:tutorId}).then(function (res) {
          console.log(res.data);
          that.classform=res.data.data;
          that.dialogFormVisible2 = true;
        })
      },
      updatetutor:function (classform) {
        console.log(classform)
        let that = this;
        if (that.classform.tutorName != ''&&that.classform.identity != ''&&that.classform.craft != ''){
          this.$http.post('/company/tutor/updatetutor',{
            tutorId:classform.id,
            companyAccount:companyAccount,
            name:classform.tutorName,
            identity:classform.identity,
            craft:classform.craft,
            sex:classform.sex,
            phone:classform.phone,
            profile:classform.profile
          }).then(function(res){
            if(res.data.data == 1){
              // alert("导师信息修改成功！");
              common.$emit('showResList',"导师信息修改成功！");

            }else{
              alert("导师信息修改失败！");
            }
            // that.getmajordata();
            // that.dialogFormVisible2 = false;

          }).catch(function (error) {
            console.log(error);
          });
        }else{
          alert("姓名、身份证号、工种不能为空！");
        }
      },
      deletetutor:function(tutorId){
        console.log(tutorId)
        this.$confirm('此操作将永久删除该导师信息, 是否继续?', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        }).then(() => {
          let that=this;
          this.$http.post('/company/tutor/deletetutor',{tutorId:tutorId}).then(function (res) {
            if (res.data.message==1){
              // alert("删除班级成功！");
              common.$emit('showClass',"导师信息删除成功！");
            }
          })
        }).catch(() => {
          alert("已取消删除！");
        });
      },
    },
    created(){
      companyAccount=this.power.username;
      this.geteditacompany(companyAccount);
      common.$on('showResList', (data) => {
        this.class1();
        this.dialogFormVisible2 = false;
        this.isActiveTwo = true;
        this.isActiveOne = false;
      })
      common.$on('showClass', (data) => {
        this.class1();
        this.dialogFormVisible1 = false;
        this.isActiveOne = true;
        this.isActiveTwo = false;
      })
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
    /*padding: 100px 50px;*/
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
  .btn1 {
    font-size: 16px;
    padding: 10px 10px;
    border-top-left-radius: 3px;
    border-top-right-radius: 3px;
    border: 1px solid #E5E7E9;
    cursor: pointer;
    background: #fff;
    margin-bottom: -1px;
    color: #000;
    width: 120px;
    /*margin-right: 0px;*/
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
    background-color: #F1F1F1;
    text-align: center;
  }

  table, td {
    border: solid 1px #ccc;
    padding: 5px;
    text-align: center;
    font-size: 14px;
  }

  .display1 {
    padding-left: 5px;
    padding-top: 10px;
  }

  .display2 {
    border: solid 1px #E5E7E9;
    height: 600px;
    /*text-align: center;*/
    width: 98%;
    padding-left: 5px;
    padding-right: 5px;
    background-color: #fff;
  }

  .sort {
    color: #1C93FC;
  }
  .sort:hover {
    color: #5CB0FA;
  }

  .btn1 {
    font-size: 16px;
    padding: 10px 10px;
    border-top-left-radius: 3px;
    border-top-right-radius: 3px;
    border: 1px solid #E5E7E9;
    cursor: pointer;
    background: #fff;
    margin-bottom: -1px;
    color: #000;
    width: 120px;
    /*margin-right: 0px;*/
  }
  .active {
    color: #01A6FE;
  }

  .input-class {
    width: 150px;
    height: 30px;
  }


  .btn2 {
    margin-left: 0px;
  }

  .btn3 {
    width: 80px;
    padding: 7px;
    font-size: 14px;
    border-radius: 3px;
    border: none;
    color: white;
    background-color: #338FFC;
    float: left;
    margin-left: 5px;
    margin-top: 15px;
    margin-bottom: 5px;
  }

  .btn3:hover {
    background-color: #5FA7FE;
  }

  .btn4{
    width:90px;
    padding:7px;
    font-size: 14px;
    border-radius: 3px;
    border:none;
    color:white;
    background-color:#FA4E28 ;
    float: right;
    margin-right: 15px;
    margin-top: 15px;
    /*margin-bottom: 5px;*/
  }

  .btn4:hover{
    background-color:#FC6F4F;
  }

  .btn4:hover {
    background-color: #FC6F4F;
  }

  .page {
    text-align: center;
  }

  .meeting{
    float:left;
    margin:14px 0 10px 0;
    font-weight: bold;
    background-color: #00AAFF;
    border:solid 1px #00AAFF;
    border-radius: 5px;
    width: 20%;
    padding:2px;
  }
</style>
