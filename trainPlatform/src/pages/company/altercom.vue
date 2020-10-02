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
          <p class="inputdiv" v-show="!isEdit1"> {{companyList.comName}}</p>
          <input v-show="isEdit1" class="inputdiv" type="text"  v-model="companyList.comName">
        </div>
        <!--<div>-->
          <!--<label>企业账户</label>-->
          <!--<p class="inputdiv" v-show="!isEdit1"> {{companyList.comAccount}}</p>-->
          <!--<input v-show="isEdit1" class="inputdiv" type="text"  v-model="companyList.comAccount">-->
        <!--</div>-->
        <div>
          <label>企业地址</label>
          <p class="inputdiv" v-show="!isEdit1"> {{companyList.comAddress}}</p>
          <input v-show="isEdit1" class="inputdiv" type="text"  v-model="companyList.comAddress">
        </div>
        <div>
          <label>企业电话</label>
          <p class="inputdiv" v-show="!isEdit1"> {{companyList.comPhone}}</p>
          <input v-show="isEdit1" class="inputdiv"  v-model="companyList.comPhone" >
        </div>
        <div>
          <label>企业邮箱</label>
          <p class="inputdiv" v-show="!isEdit1"> {{companyList.comEmail}}</p>
          <input v-show="isEdit1" class="inputdiv" type="text" v-model="companyList.comEmail">
        </div>
        <div>
          <label>企业简介</label>
          <p class="inputdiv" v-show="!isEdit1"> {{companyList.introdution}}</p>
          <input v-show="isEdit1" class="inputdiv" type="text" v-model="companyList.introdution">
        </div>

      </div>
      <div class="add-btn">
        <button  class="btn2 icon-huanyihuan" v-on:click="reset()">重置</button>
        <button type="submit" class="btn1 icon-duigou" v-on:click="submit(companyList.id,companyList.comName,companyList.comAddress,companyList.comPhone,companyList.comEmail,companyList.introdution)">确认
        </button>
      </div>

    </div>
  </div>

</template>

<script>
  var companyId
  export default {
    name: 'altercom',
      data () {
      return {
        companyList: [],
        isEdit: true,
        isEdit1: true

      }
    },
    methods: {
      geteditcompany: function (companyId) {
        let that = this
        this.$http.post('/company/company/geteditcompany', {companyId: companyId}).then(function (res) {
          that.companyList = res.data.data[0]
          console.log(res)
          console.log(that.companyList)
        })
      },
      reset: function () {
        this.companyList = []
             },
      back () {
        this.$router.push({ path: '/company?name=企业管理' })
      },
      submit: function (companyId, a1, a3, a4, a5, a6) {
        let that = this
        if (a1 != '' && a3 != '' && a4 != '' && a5 != '' && a6 != '') {
          this.$http.post('/company/company/altercompany', {
            companyId: companyId,
            companyName: a1,
            companyAddress: a3,
            companyPhone: a4,
            companyEmail: a5,
            companyIntro: a6
          }).then(function (res) {
            console.log(res.data)
            if (res.data.message == 'success') {
              alert('企业信息修改成功！')
            } else {
              alert('企业信息修改失败！')
            }
            that.geteditcompany(companyId)
            that.isEdit1 = false
          }).catch(function (error) {
            console.log(error)
          })
        } else if (a1 == '' || a3 == '' || a4 == '' || a5 == '' || a6 == '') {
          alert('企业信息不能为空！')
        }
      }
    },
    created () {
      companyId = this.$route.query.companyId
      console.log(companyId)
      this.isEdit1 = true
      this.geteditcompany(companyId)
    },
    watch: {
      '$route' (to, from) {
        this.$router.go(0)
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
