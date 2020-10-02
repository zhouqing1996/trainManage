<template>
  <el-row class="tac" style="margin-left: 2%">
    <el-col :span="4">
      <h5></h5>
      <el-menu
        default-active="0"
        class="el-menu-vertical-demo"
        @open="handleOpen"
        @close="handleClose">
        <el-menu-item index="0">
          <i class="el-icon-menu"></i>
          <span slot="title" @click="iterator = templateShow(0)">模板类别</span>
        </el-menu-item>
        <el-menu-item index="1">
          <i class="el-icon-setting"></i>
          <span slot="title" @click="iterator = templateShow(1)">企业相关调查</span>
        </el-menu-item>
        <el-menu-item index="2">
          <i class="el-icon-setting"></i>
          <span slot="title" @click="iterator = templateShow(2)">学生相关调查</span>
        </el-menu-item>
        <el-menu-item index="3">
          <i class="el-icon-setting"></i>
          <span slot="title" @click="iterator = templateShow(3)">教师相关调查</span>
        </el-menu-item>
        <el-menu-item index="4">
          <i class="el-icon-setting"></i>
          <span slot="title" @click="iterator = templateShow(4)">企业对学校的调查</span>
        </el-menu-item>
        <el-menu-item index="5">
          <i class="el-icon-setting"></i>
          <span slot="title" @click="iterator = templateShow(5)">毕业生追踪调查</span>
        </el-menu-item>
        <el-menu-item index="6">
          <i class="el-icon-setting"></i>
          <span slot="title" @click="iterator = templateShow(6)">其他</span>
        </el-menu-item>
      </el-menu>
    </el-col>
    <div class="container" >
    <el-col>
      <h5></h5>
        <div class="list-wrapper">
          <ul>
            <li>标题</li>
            <li>操作</li>
            <li><p @click="$router.push({path:'/requirement/templateadd',query:{id: 0}})">+ 新建模板</p></li>
          </ul>
          <ul v-for="item in surveyList">
            <li v-text="item.title"></li>
            <li>
              <router-link tag="span" :to="{ path: address , query: { id: item.id,flag:10}}">
                预览模板
              </router-link>
              <span v-if="!item.state" @click="iterator = editItem(item); iterator.next()">编辑模板</span>
              <span  v-if="!item.state" @click="iterator = showPrompt1(item)">修改类别</span>
              <span @click="iterator = deleteItem(item); iterator.next()">删除</span>
            </li>
            <li></li>
          </ul>
        </div>
        <div class="overlay" v-show="isShowPrompt">
          <div class="prompt-box">
            <header>
              <span>提示</span>
              <a href="javascript:;" @click="isShowPrompt = false">&times;</a>
            </header>
            <p>{{ promptText }}</p>
            <footer>
              <span @click="iterator.next(); isShowPrompt = false">确定</span>
              <span @click="isShowPrompt = false">取消</span>
            </footer>
          </div>
        </div>
    </el-col>
    </div>
  </el-row>
</template>

<script>
    export default {
        name: "Templatelist",
        data() {
          return {
            quData:{},
            surveyList: [],
            quList: [],
            iterator: {},
            totalpage: '',
            isShowPrompt: false,
            isShowPrompt1: false,
            templateType:'',
            promptText: '',
            address:''
          }
        },

        created(){
          this.flag = this.$route.query.flag;
          console.log(this.flag)
          this.getdata();
        },

        methods: {
          getdata:function(){
            let that=this;
            this.$http.get('/requirement/template/gettemplate').then(function (res) {
              console.log(res.data);
              if (res.data.data == false){
              } else{
                that.surveyList=res.data.data[0];
              }
            })
            if (that.flag == 10){
              that.address = '/requirement/templateview';
            } else{
              that.address = '/requirement/templateview0';
            }
          },

          handleOpen(key, keyPath) {
            console.log(key, keyPath);
          },
          handleClose(key, keyPath) {
            console.log(key, keyPath);
          },

          templateShow:function(type){
            console.log(type);
            let that=this;
            this.$http.post('/requirement/require/templateshow',{
              page:1,
              type:type,
            }).then(function (res) {
              console.log(res.data);
              that.surveyList=res.data.data[0];
            })
          },


          showPrompt(text) {
            this.promptText = text;
            this.isShowPrompt = true;
          },

          showPrompt1(item) {
            this.quData =item;
            this.isShowPrompt1 = true;
          },

          editClass(templateType) {
            console.log(templateType);
            this.quData.type = templateType;
            console.log(this.quData);
            this.$http.post('/requirement/require/editclass', {
              quData:this.quData
            }).then((res)=>{
              console.log(res.data);
              if(res.data.message==1){
                alert("模板修改成功！");
                this.$router.push({ path:'/requirement/templatelist'});
              }else{
                alert("模板修改失败！");
              }
            });
            this.isShowPrompt1 = false;
          },

          *deleteItem(item) {
            console.log(item);
            yield this.showPrompt(`确认要删除《${item.title}》？`);
            this.$http.post('/requirement/require/deletesurvey',{id:item.id}).then(function (res) {
              console.log(res.data);
              if (res.data.message==0){
                alert("问卷删除成功！");
                location.reload();
              }
            })
          },

          *deleteCheckedItems() {
            let checkedList = this.quList.filter( item => item.checked);
            if (!checkedList.length) {
              return;
            }
            yield this.showPrompt('确认要删除所选问卷？');
            yield this.quList = this.quList.filter( item => !item.checked);
          },

          *editItem(item) {
            yield this.showPrompt(`确认要编辑《${item.title}》？`);
            yield this.$router.push({path:'/requirement/templateedit',query:{id: item.id}});
          }
        },
    }
</script>

<style scoped lang="scss">
  @import '../../style/public.scss';
  .container {
    margin-left: 0px;
    width: 80%;
    color: #555;
    float: left;
  }

  .add-wrapper {
    height: 20rem;
    @include flex-center;
    @include wrap-background;

    p {
      padding: 1rem 3rem;
      @include add-btn;
    }

  }

  .list-wrapper {
    overflow: hidden;
    @include wrap-background;
    @include check-icon;

    ul {
      display: flex;
      height: 6rem;
      line-height: 6rem;
      @include nomal-btn;

      &:nth-of-type(1) {
        background-color: #f2f2f2;

        p {
          display: inline-block;
          width: 7rem;
          height: 3rem;
          margin-left: 11rem;
          line-height: 3rem;
          text-align: center;
          @include add-btn;
        }

      }

      &:not(:first-child) {
        border-bottom: 1px solid #eee;

        &:hover {
          background-color: $light-orange;
        }

      }

      li {
        &:nth-of-type(1) {
          text-align: center;
          flex: 1;
        }

        &:nth-of-type(2) {
          text-align: center;
          flex: 2;
        }

        &:nth-of-type(3) {
          margin-right: 5%;
          flex: 0.4;
        }

        &:nth-of-type(4) {
          flex: 2;
        }

        &:nth-of-type(5) {
          flex: 7;
        }

      }

      .releasing {
        color: $light-green;
      }

    }

    div {
      display: flex;
      height: 7rem;
      line-height: 6rem;
      @include nomal-btn();

      p {
        &:nth-of-type(1) {
          text-align: center;
          flex: 1;
        }

        &:nth-of-type(2) {
          flex: 14;

          span {
            margin-left: 2rem;
          }

        }

      }

    }

  }
</style>
