<template>
  <div class="display1">
    <button class="btn1" v-on:click="major1" v-bind:class="{ active: isActiveOne }">专业信息</button>
    <button class="btn1" v-on:click="class1" v-bind:class="{ active: isActiveTwo }">班级信息</button>
    <div class="display2" v-show="major">
      <div class="searchmem">
        <div class="meeting" >
          <el-input v-model="majorName" placeholder="请输入专业名称" size="mini"></el-input>
        </div>
        <button class="btn3 icon-sousuo" v-on:click="querymajor()">
          搜索
        </button>
        <button class="btn3" type="text" @click="dialogFormVisible = true;form.majorName='';form.majorProfile='';">添加</button>
        <el-dialog title="添加专业信息" :visible.sync="dialogFormVisible">
          <el-form :model="form">
            <el-form-item label="专业名称" :label-width="formLabelWidth">
              <el-input style="width: 350px;" v-model="form.majorName" auto-complete="off"></el-input>
            </el-form-item>
            <el-form-item label="专业简介" :label-width="formLabelWidth">
              <el-input
                type="textarea"0
                style="width: 350px;row-span: 8;"
                placeholder="请输入内容"
                v-model="form.majorProfile">
              </el-input>
            </el-form-item>
          </el-form>
          <div slot="footer" style="align-content: center" class="dialog-footer">
            <el-button type="primary" @click="submit(form,1)">提交</el-button>
            <el-button @click="resetmajor()">重置</el-button>
          </div>
        </el-dialog>
        <el-dialog title="修改专业信息" :visible.sync="dialogFormVisible2">
          <el-form :model="form">
            <el-form-item label="专业名称" :label-width="formLabelWidth">
              <el-input style="width: 350px;" v-model="form.majorName" auto-complete="off"></el-input>
            </el-form-item>
            <el-form-item label="专业简介" :label-width="formLabelWidth">
              <el-input
                type="textarea"
                style="width: 350px;row-span: 8;"
                placeholder="请输入内容"
                v-model="form.majorProfile">
              </el-input>
            </el-form-item>
          </el-form>
          <div slot="footer" style="align-content: center" class="dialog-footer">
            <el-button type="primary" @click="submit(form,2)">提交</el-button>
            <el-button @click="resetmajor()">重置</el-button>
          </div>
        </el-dialog>
      </div>
      <table id="memberStastics">
        <tr>
          <th>序号</th>
          <th>专业名称</th>
          <th>专业简介</th>
          <th>编辑</th>
          <th>删除</th>
        </tr>
        <tr v-for="(major,index) in majorInfo">
          <td>{{index+1}}</td>
          <td>{{major.major}}</td>
          <td>{{major.profile}}</td>
          <!--<td>{{manager[index]}}</td>-->
          <td>
            <a @click="geteditmajor(major.id)">修改</a>
          </td>
          <td>
            <span @click="deletemajor(major.id)">
              删除
            </span>
          </td>
        </tr>
      </table>
    </div>
    <div class="display2" v-show="classes">
      <div class="searchmem">
        <button class="btn3" type="text" @click="dialogFormVisible1 = true;classform.grade='';classform.major='';classform.class=''">添加</button>
        <el-dialog title="班级信息" :visible.sync="dialogFormVisible1">
          <el-form :model="classform">
            <el-form-item label="年级" :label-width="formLabelWidth">
              <el-input style="width: 350px;" v-model="classform.grade" auto-complete="off"></el-input>
            </el-form-item>
            <el-form-item label="专业" :label-width="formLabelWidth">
              <select v-model="classform.major" style="font-size:15px;width:350px;height:25px" >
                <option disabled value="">选择</option>
                <option v-for="item in majorInfo" :value=item.id>{{item.major}}</option>
              </select>
            </el-form-item>
            <el-form-item label="班级" :label-width="formLabelWidth">
              <el-input style="width: 350px;" v-model="classform.className" auto-complete="off"></el-input>
            </el-form-item>
          </el-form>
          <div slot="footer" style="align-content: center" class="dialog-footer">
            <el-button type="primary" @click="insertclass(classform)">提交</el-button>
            <el-button @click="resetclass()">重置</el-button>
          </div>
        </el-dialog>
      </div>
      <table id="studentStastics">
        <tr>
          <th>序号</th>
          <th>专业 </th>
          <th>年级</th>
          <th>班级</th>
          <th>删除</th>
        </tr>
        <tr v-for="(class1,index) in classInfo">
          <td>{{index+1}}</td>
          <td>{{class1.major}}</td>
          <td>{{class1.grade}}</td>
          <td>{{class1.major}}({{class1.className}})</td>
          <td>
            <span @click="deleteclass(class1.id)">删除</span>
          </td>
        </tr>
      </table>
    </div>
  </div>
</template>

<script>
  import { common } from '../../../main'
  export default {
    name: 'Major',
    data(){
      return {
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
          grade:'',
          major: '',
          className: '',
        },
        formLabelWidth: '120px'
      }
    },
    methods: {
      major1: function () {
        this.major = true;
        this.classes = false;
        this.isActiveOne= true;
        this.isActiveTwo= false;
      },
      class1: function () {
        let that = this;
        this.$http.get('/department/class/getdata?page=1').then(function (res) {
          that.classInfo = res.data.data;
        })
        this.major = false;
        this.classes = true;
        this.isActiveOne= false;
        this.isActiveTwo= true;
      },
      getmajordata:function(){
        let that=this;
        this.$http.get('/department/major/getdata?page=1').then(function (res) {
          that.majorInfo=res.data.data['major'];
          that.manager=res.data.data['manager'];
        })
        this.major = true;
        this.classes = false;
        this.isActiveOne= true;
        this.isActiveTwo= false;
      },
      insertmajor:function () {
        let that = this;
        if (that.form.majorName!=''){
          this.$http.post('/department/major/insertmajor', {
            majorName:that.form.majorName,
            majorProfile:that.form.majorProfile,
          }).then((res)=>{
            console.log(res)
            if(res.data.message==1){
              that.getmajordata();
              that.dialogFormVisible = false;
              common.$emit('showResList',"专业信息添加成功！");
            }else{
              alert("专业信息添加失败！");
            }
          });
        }else{
          alert("专业名不能为空！");
        }
      },
      deletemajor:function(majorId){
        this.$confirm('此操作将永久删除该专业, 是否继续?', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        }).then(() => {
          let that=this;
          this.$http.post('/department/major/deletemajor',{majorId:majorId}).then(function (res) {
            console.log(res);
            if (res.data.message==1){
              alert("删除专业成功！");
              common.$emit('showResList',"删除专业成功！");
            }
          })
        }).catch(() => {
        });
      },
      geteditmajor:function(majorId){
        this.majorId = majorId;
        console.log(majorId)
        let that = this;
        this.$http.post('/department/major/geteditmajor',{majorId:majorId}).then(function (res) {
          console.log(res.data)
          that.form.majorName = res.data.data.major;
          that.form.majorProfile = res.data.data.profile;
          that.dialogFormVisible2 = true;
        })
      },
        updatemajor:function (majorId) {
        console.log(majorId)
        let that = this;
        if (that.form.majorName != ''){
          this.$http.post('/department/major/altermajor', {
            majorId:majorId,
            majorName:that.form.majorName,
            profile:that.form.majorProfile,
          }).then(function(res){
            if(res.data.data == 1){
              alert("专业信息修改成功！");
            }else{
              alert("专业信息修改失败！");
            }
            that.getmajordata();
            that.dialogFormVisible2 = false;
            common.$emit('showResList',"专业信息修改！");
          }).catch(function (error) {
            console.log(error);
          });
        }else{
          alert("专业名称不能为空！");
        }
      },
      submit:function(form,flag){
        console.log(form,flag)
        if (flag == 1){
          this.insertmajor()
        }else if(flag==2){
          this.updatemajor(this.majorId)
        }
      },
      querymajor:function(){
        this.$http.post('/department/major/querymajor', {majorName: this.majorName}).then(res => {
          this.majorInfo = res.data.data['major'];
          this.manager = res.data.data['manager'];
        }).catch(function (error) {
          console.log(error);
        });
      },
      resetmajor:function(){
        this.form.majorName='';
        this.form.majorProfile='';
      },
      insertclass:function(classform){
        let that =this;
        if (that.classform.grade != '' && that.classform.major != '' && that.classform.className != ''){
          that.$http.post('/department/class/insertclass', {
            grade:that.classform.grade,
            major:that.classform.major,
            class:that.classform.className,
          }).then((res)=>{
            if(res.data.message == 1){
              alert("班级信息添加成功")
              common.$emit('showClass',"班级信息添加成功！");
              that.classform.className='';
              that.dialogFormVisible1 = false;
            }else{
              alert("班级信息添加失败！");
            }
          });
        }else{
          alert("班级信息不能为空！");
        }
      },
      deleteclass:function(classId){
        this.$confirm('此操作将永久删除该班级, 是否继续?', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        }).then(() => {
          let that=this;
          this.$http.post('/department/class/deleteclass',{classId:classId}).then(function (res) {
            if (res.data.message==1){
              alert("删除班级成功！");
              common.$emit('showClass',"班级信息修改！");
            }
          })
        }).catch(() => {
          alert("已取消删除！");
        });
      },
      resetclass:function(){
        this.classform.grade='';
        this.classform.major='';
        this.classform.className='';
      },
    },

    created() {
      this.getmajordata();
      common.$on('showResList', (data) => {
        this.getmajordata();
      })
      common.$on('showClass', (data) => {
        this.class1();
      })
    },
  }
</script>

<style scoped>
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

