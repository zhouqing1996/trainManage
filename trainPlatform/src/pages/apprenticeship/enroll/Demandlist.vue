<template>
  <div class="container">
    <el-button style="margin-left: 40px" type="warning" @click="$router.push({path:'/requirement/surveyindex',query:{id: 0,flag:40}})">+ 新建需求调查</el-button>
    <el-button style="margin-left: 40px" type="warning" @click="$router.push({path:'/apprenticeship/enroll/assign'})">+ 确定招实专业及指标</el-button>
    <div v-for="(item,index) in surveyList" :key="index">
      <el-card class="box-card">
        <div slot="header" class="clearfix">
          <span >{{item.title}}</span>
          <span style="margin-left: 10px;">ID:{{item.id}}</span>
          <span style="float: right;margin-left: 10px;color: #df5000">答卷数量:{{ansNum[index]}}</span>
          <span v-if="item.endTime=='unlimited'" style="float: right;margin-left: 10px;">时间无限制</span>
          <span v-if="item.endTime!='unlimited'" style="float: right;margin-left: 10px;">{{item.endTime}}</span>
          <span style="float: right;color: #df5000">
            <a v-if="item.status==1">未发布</a>
            <a v-if="item.status==2">已发布</a>
            <a v-if="item.status==3">已结束</a>
          </span>
          <!--<el-button style="float: right; padding: 3px 0" type="text">操作按钮</el-button>-->
        </div>
        <div class="text item">
          <router-link :to="{ path: '/apprenticeship/enroll/demandview', query: { id: item.id,flag:40,status:item.status }}">
            <el-button plain size="mini"><i  class="iconchakan"></i>查看</el-button>
          </router-link>
          <el-button @click="iterator = editItem(item); iterator.next()" plain size="mini" v-show="item.status==1"><i  class="iconbianji1"></i>编辑</el-button>
          <el-button @click="iterator = publishItem(item,1); iterator.next()" plain size="mini" v-show="item.status==1"><i  class="iconfabu1" @click="iterator = publishItem(item,1); iterator.next()"></i>发布问卷</el-button>
          <router-link :to="{ path: '/apprenticeship/enroll/demandpublish', query: { id: item.id }}" >
            <el-button plain size="mini" v-show="item.status==2"><i  class="iconxitongguzhang"></i>发布管理</el-button>
          </router-link>
          <el-button @click="iterator = publishItem(item,2); iterator.next()" plain size="mini" v-show="item.status==2"><i  class="iconfanhui"></i>取消发布</el-button>
          <!--<el-button @click="iterator = temconversion(item.id);iterator.next()" plain size="mini"><i  class="icongongneng_fabuxuqiu"></i>转化模板</el-button>-->
          <router-link :to="{ path: '/apprenticeship/enroll/demandstatistics', query: { id: item.id,flag:10 }}">
            <el-button plain size="mini" v-show="item.status==2 || item.status==3"><i  class="icontongji-"></i>数据统计</el-button>
          </router-link>
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
</template>

<script>
  import Vue from 'vue'
  import Store from '../../../store.js'
  import data from '../../../data.js'
  import { common } from '../../../main'

  export default {
    name: "Demandlist",
    data() {
      return {
        surveyList:[],
        quList: [],
        iterator: {},
        totalpage:'',
        isShowPrompt: false,
        promptText: '',
        flag:40,
        ansNum:''
      }
    },

    created() {
      console.log(localStorage.getItem('Ip'));
      this.user = this.$store.state.mutations.user;
      this.getdata();
    },

    methods: {
      getdata:function(){
        let that=this;
        that.$http.post('/tracking/track/getdata',{
          page:1,
          username:that.user,
          flag:that.flag
        }).then(function (res) {
          console.log(res.data);
          that.surveyList=res.data.data[0];
          that.ansNum=res.data.data[1];
          that.totalpage=res.data.data[2];
          let curTime = Date.now();
          that.surveyList.forEach((item) => {
            if (item.status == 2) {
              that.itemTime = new Date(item.endTime.replace(/-/g, ','))*1;
              if (that.itemTime < curTime) {
                item.status = 3;
              }
            }
          })
        })
      },

      checkItem(item, flag = null) {
        if (typeof item.checked === 'undefined') {
          Vue.set(item, 'checked', true);
        }
        else if (flag !== null) {
          item.checked = !flag;
        }
        else {
          item.checked = !item.checked;
        }
      },

      checkAll(flag) {
        this.quList.forEach( item => {
          this.checkItem(item, flag)
        });
      },

      showPrompt(text) {
        this.promptText = text;
        this.isShowPrompt = true;
      },

      *publishItem(item,flag) {
        var state;
        if(flag==1){
          yield this.showPrompt(`确认要发布《${item.title}》？`);
          state = 2;
        }else{
          yield this.showPrompt(`确认要取消发布《${item.title}》？`);
          state = 1;
        }
        let that = this;
        that.$http.post('/requirement/require/publishsurvey',{
          id:item.id,
          status:state
        }).then(function (res) {
          console.log(res.data);
          if(res.data.message==1){
            that.getdata();
            if (flag == 1){
              alert("问卷发布成功！");
            } else{
              alert("问卷取消发布！");
            }
          }else{
            if (flag == 1){
              alert("问卷发布失败！");
            } else{
              alert("问卷取消发布失败！");
            }
          }
        })
      },

      *deleteItem(item) {
        yield this.showPrompt(`确认要删除《${item.title}》？`);
        let that = this;
        that.$http.post('/requirement/require/deletesurvey',{id:item.id,flag:10}).then(function (res) {
          console.log(res.data);
          if (res.data.message!=null){
            that.getdata();
            alert("问卷删除成功！");
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
        yield this.$router.push({path:'/requirement/surveyedit',query:{id: item.id,flag:40}});
      }
    }

  }
</script>

<style scoped lang="scss">
  @import '../../../style/public';
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
    width: 100%;
    padding-top: 10px;
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
          flex: 3;
        }

        &:nth-of-type(2) {
          text-align: center;
          flex: 1;
        }

        &:nth-of-type(3) {
          text-align: center;
          flex: 1;
        }

        &:nth-of-type(4) {
          text-align: center;
          flex: 5;
        }

        &:nth-of-type(5) {
          text-align: center;
          flex: 1;
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
            margin-left: 1rem;
          }

        }

      }

    }

  }

</style>
