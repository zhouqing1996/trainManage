<template>
  <div>
    <el-dialog title="发布招聘" :visible.sync="dvisible" @close='closeDialog'>
      <el-form :model="form">
        <el-form-item label="企业名称" :label-width="formLabelWidth">
          <el-input v-model="form.name"></el-input>
        </el-form-item>
        <el-form-item label="企业账户" :label-width="formLabelWidth">
          <el-input type="textarea" v-model="form.account"></el-input>
        </el-form-item>
        <el-form-item label="企业地址" :label-width="formLabelWidth">
          <el-input type="textarea" v-model="form.address"></el-input>
        </el-form-item>
        <el-form-item label="企业电话" :label-width="formLabelWidth">
          <el-input type="textarea" v-model="form.phone"></el-input>
        </el-form-item>
        <el-form-item label="企业简介" :label-width="formLabelWidth">
          <el-input type="textarea" v-model="form.introdution"></el-input>
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
    name: 'Addcom1',
    props: ['dialogVisible', 'major'],
    data () {
      return {
        dvisible: false,
        form: {
          name: '',
          account: '',
          address: '',
          phone: '',
          email: '',
          introdution: ''
        },
        formLabelWidth: '150px'
      }
    },
    methods: {
      reset: function () {
        this.form.name = ''
        this.form.account = ''
        this.form.address = ''
        this.form.phone = ''
        this.form.email = ''
        this.form.introdution = ''
      },
      visible (param) {
        if (param == 1) {
          let that = this
          if (this.form.name != '' && this.form.account != '' && this.form.phone != '' && this.form.email != '') {
            this.$http.post('/company/company/insertcompany', {
              companyName: that.form.name,
              companyAccount: that.form.account,
              companyAddress: that.form.address,
              companyPhone: that.form.phone,
              companyEmail: that.form.email,
              companyIntro: that.form.introdution
            }).then((res) => {
              console.log(res)
              that.$emit('child', 1)
            })
          } else if (this.form.name == '' && this.form.account == '' && this.form.phone == '' && this.form.email == '') {
            alert('企业信息不能为空！')
          }
        }
      },
      closeDialog () {
        this.$emit('child', 0)
      }
    },
    mounted () {
      let that = this
      if (this.dialogVisible) {
        this.dvisible = this.dialogVisible
        this.allmajor = this.major
      }
    }
    // watch: {
    //   data: {
    //     handler(newValue, oldValue) {
    //       console.log(newValue);
    //       console.log(oldValue);
    //       if (newValue!="add") {
    //         this.isEdit=true,
    //           this.addname=this.data.comName;
    //         this.addaccount=this.data.comAccount;
    //         this.addaddress=this.data.comAddress;
    //         this.addphone=this.data.comPhone;
    //         this.addemail=this.data.comEmail;
    //         this.addpassword=this.data.comPassword;
    //
    //       }else{
    //         this.isEdit=false;
    //           this.addname="";
    //         this.addaccount="";
    //         this.addaddress="";
    //         this.addphone="";
    //         this.addemail="";
    //         this.addpassword="";
    //       }
    //     },
    //     deep: true//未知变量
    //   }
    // }
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
