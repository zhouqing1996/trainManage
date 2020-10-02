<template>
<!--  //未使用该文件-->
  <div>
    <el-dialog title="导师添加" :visible.sync="dvisible"  @close='closeDialog'>
      <el-form :model="form">
        <el-form-item label="姓名" :label-width="formLabelWidth">
<!--          <el-input v-model="form.name"></el-input>-->
          <el-input style="width: 350px;" v-model="form.name" auto-complete="off"></el-input>
        </el-form-item>
        <el-form-item label="身份证号" :label-width="formLabelWidth">
<!--          <el-input type="textarea" v-model="form.identity"></el-input>-->
          <el-input style="width: 350px;" v-model="form.identity" auto-complete="off"></el-input>
        </el-form-item>
<!--        <el-form-item label="公司" :label-width="formLabelWidth">-->
        <el-form-item label="公司" :label-width="formLabelWidth">
          <select v-model="form.company" style="font-size:15px;width:350px;height:25px" >
            <option disabled value="">选择</option>
            <option v-for="item in companyList" :value=item.comAccount>{{item.comName}}</option>
          </select>
<!--          <el-input type="textarea" v-model="form.company"></el-input>-->
        </el-form-item>
        <el-form-item label="性别" :label-width="formLabelWidth">
          <select v-model="form.sex" style="font-size:15px;width:180px;" >
<!--            <option disabled value="男">选择</option>-->
            <option value="1">男</option>
            <option value="2">女</option>
          </select>
<!--          <el-input type="textarea" v-model="form.sex"></el-input>-->
        </el-form-item>
        <el-form-item label="岗位" :label-width="formLabelWidth">
<!--          <el-input type="textarea" v-model="form.craft"></el-input>-->
          <el-input style="width: 350px;" v-model="form.craft" auto-complete="off"></el-input>
        </el-form-item>
        <el-form-item label="电话" :label-width="formLabelWidth">
<!--          <el-input type="textarea" v-model="form.phone"></el-input>-->
          <el-input style="width: 350px;" v-model="form.phone" auto-complete="off"></el-input>
        </el-form-item>
        <el-form-item label="简介" :label-width="formLabelWidth">
          <el-input type="textarea" v-model="form.profile"></el-input>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="visible(0)">取 消</el-button>
        <el-button type="primary" @click="visible(1)">确 定</el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script>
    import { common } from '../../main'
    export default {
        name: "Addtutor",
        props: ['dialogVisible2','companyList'],
        data() {
            return {
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
                formLabelWidth: '120px',
            }
        },
        methods: {
            reset:function(){
                this.form.name='';
                this.form.identity='';
                this.form.sex='';
                this.form.company='';
                this.form.craft='';
                this.form.phone='';
                this.form.profile='';
            },
            visible: function (param) {
                if (param == 1) {
                    let that=this;
                    if (this.form.name!=''&&this.form.identity!=''&&this.form.phone!=''&&this.form.company!=''&&this.form.craft!=''&&this.form.sex!=''&&this.form.profile!=''){
                        this.$http.post('/company/tutor/inserttutorroot', {
                            tutorName:that.form.name,
                            identity:that.form.identity,
                            sex:that.form.sex,
                            phone:that.form.phone,
                            profile:that.form.profile,
                            craft:that.form.craft,
                            company:that.form.company,
                        }).then((res)=>{
                            console.log(res)
                            that.$emit('child2', 1)
                        });
                    }
                    else {
                        alert("导师信息不能为空！");
                    }
                }
                else{
                    this.closeDialog();
                }
            },

            closeDialog () {
                this.$emit('child2', 0)
            }
        },
        mounted () {
            let that = this
            if (this.dialogVisible2) {
                this.dvisible = this.dialogVisible2
                this.allcompany = this.companyList
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
