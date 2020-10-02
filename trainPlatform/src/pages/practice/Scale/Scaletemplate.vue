<template>
  <div class="container">
    <el-button style="margin-left: 40px" type="warning" @click="$router.push({path:'/requirement/templateadd',query:{id: 0,flag:21}})">+ 新建量表模板</el-button>
    <div v-if="surveyList1" v-for="(item,index) in surveyList1" :key="index">
      <el-card class="box-card">
        <div slot="header" class="clearfix">
          <span >{{item.title}}</span>
          <span style="margin-left: 10px;">ID:{{item.id}}</span>
          <!--<span style="float: right;margin-left: 10px;color: #df5000">回收数量:{{ansNum[index]}}</span>-->
          <!--<span v-if="item.endTime=='unlimited'" style="float: right;margin-left: 10px;">时间无限制</span>-->
          <!--<span v-if="item.endTime!='unlimited'" style="float: right;margin-left: 10px;">{{item.endTime}}</span>-->
          <!--<span style="float: right;color: #df5000">-->
            <!--<a v-if="item.status==1">未发布</a>-->
            <!--<a v-if="item.status==2">已发布</a>-->
            <!--<a v-if="item.status==3">已结束</a>-->
          <!--</span>-->
          <!--<el-button style="float: right; padding: 3px 0" type="text">操作按钮</el-button>-->
        </div>
        <div class="text item">
          <router-link :to="{ path: '/requirement/templateview0', query: { id: item.id,flag:20 }}">
            <el-button plain size="mini"><i  class="iconchakan"></i>查看</el-button>
          </router-link>
          <el-button @click="iterator = editItem(item); iterator.next()" plain size="mini" v-show="item.status==1"><i  class="iconbianji1"></i>编辑</el-button>
          <!--<el-button @click="iterator = publishItem(item,1); iterator.next()" plain size="mini" v-show="item.status==1"><i  class="iconfabu1" @click="iterator = publishItem(item); iterator.next()"></i>发布问卷</el-button>-->
          <!--<router-link :to="{ path: '/requirement/surveypublish', query: { id: item.id }}" >-->
            <!--<el-button plain size="mini" v-show="item.status==2"><i  class="iconxitongguzhang"></i>发布管理</el-button>-->
          <!--</router-link>-->
          <!--<el-button @click="iterator = publishItem(item,2); iterator.next()" plain size="mini" v-show="item.status==2"><i  class="iconfanhui"></i>取消发布</el-button>-->
          <!--<el-button @click="iterator = temconversion(item.id);iterator.next()" plain size="mini"><i  class="icongongneng_fabuxuqiu"></i>转化模板</el-button>-->
          <!--<router-link :to="{ path: '/requirement/datastatistics', query: { id: item.id }}">-->
            <!--<el-button plain size="mini" v-show="item.status==2 || item.status==3"><i  class="icontongji-"></i>数据统计</el-button>-->
          <!--</router-link>-->
          <el-button @click="iterator = deleteItem(item); iterator.next()" plain size="mini"><i  class="iconshanchu"></i>删除</el-button>
        </div>
      </el-card>
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
  </div>
    <!--<div class="container" >-->
        <!--<div class="list-wrapper">-->
          <!--<ul>-->
            <!--<li>标题</li>-->
            <!--<li>操作</li>-->
            <!--<li><p @click="$router.push({path:'/requirement/templateadd',query:{id: 0}})">+ 新建模板</p></li>-->
          <!--</ul>-->
          <!--<ul v-for="item in surveyList">-->
            <!--<li v-text="item.title"></li>-->
            <!--<li>-->
              <!--<router-link tag="span" :to="{ path: address , query: { id: item.id,flag:10}}">-->
                <!--预览模板-->
              <!--</router-link>-->
              <!--<span v-if="!item.state" @click="iterator = editItem(item); iterator.next()">编辑模板</span>-->
              <!--<span  v-if="!item.state" @click="iterator = showPrompt1(item)">修改类别</span>-->
              <!--<span @click="iterator = deleteItem(item); iterator.next()">删除</span>-->
            <!--</li>-->
            <!--<li></li>-->
          <!--</ul>-->
        <!--</div>-->
        <!--<div class="overlay" v-show="isShowPrompt">-->
          <!--<div class="prompt-box">-->
            <!--<header>-->
              <!--<span>提示</span>-->
              <!--<a href="javascript:;" @click="isShowPrompt = false">&times;</a>-->
            <!--</header>-->
            <!--<p>{{ promptText }}</p>-->
            <!--<footer>-->
              <!--<span @click="iterator.next(); isShowPrompt = false">确定</span>-->
              <!--<span @click="isShowPrompt = false">取消</span>-->
            <!--</footer>-->
          <!--</div>-->
        <!--</div>-->
    <!--</div>-->
</template>

<script>
    export default {
      name: "Scaletemplate",
      data() {
        return {
          quData:{},
          surveyList1: [],
          ansNum:[],
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
        this.getdata();
      },

      methods: {
        getdata:function(){
          let that=this;
          this.$http.get('/requirement/template/gettemplate2').then(function (res) {
            console.log(res.data);
            if (res.data.data == false){
            } else{
              that.surveyList1=res.data.data[0];
            }
          })
          // if (this.flag == 10){
          //   that.address = '/requirement/templateview';
          // } else{
          //   that.address = '/requirement/templateview0';
          // }
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
            that.surveyList=res.data.data;
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
          yield this.$router.push({path:'/requirement/templateedit',query:{id: item.id,flag:21}});
        }
      },
    }
</script>

<style scoped lang="scss">
  @import '../../../style/public.scss';

  .text {
    font-size: 14px;
  }

  .item {
    margin-bottom: 18px;
  }

  .clearfix:before,
  .clearfix:after {
    display: table;
    content: "";
  }
  .clearfix:after {
    clear: both
  }

  .box-card {
    width: 90%;
    margin-top: 6px;
    margin-left: 40px;
  }
  .container {
    margin-left: 0px;
    padding-top: 10px;
    width: 100%;
    //margin: 6rem auto;
    color: #555;
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
    }
</script>

<style scoped>

</style>
