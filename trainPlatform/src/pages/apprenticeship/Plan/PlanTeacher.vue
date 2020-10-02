<template>
  <div class="container">
    <!--<el-button style="margin-left: 40px" type="warning" @click="$router.push({path:'/requirement/surveyindex',query:{id: 0,flag:20}})">+ 新建量表</el-button>-->
    <div v-if="surveyList" v-for="(item,index) in plan" :key="index">
      <el-card class="box-card">
        <div slot="header" class="clearfix">
          <span >{{item.title}}</span>

          <el-button style="float: right; padding: 3px 0" type="text"><a  target="_blank" :href="item.url" >
            <span class="a-inner"  ><i class="el-icon-document"></i>编辑</span>
          </a></el-button>
          <span style="float: right; padding:0 3px ">操作:</span>
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
    name: "PlanTeacher",
    data() {
      return {
        surveyList:[],
        quList: [],
        iterator: {},
        totalpage:'',
        isShowPrompt: false,
        promptText: '',
        flag:20 ,
        ansNum:'',
        plan:[]
      }
    },

    created() {
      this.user = this.$store.state.mutations.user;
      this.getdata();
    },

    methods: {
      getdata:function(){
        this.plan = [];
        this.plan[0]=[];
        this.plan[1]=[];
        this.plan[2]=[];
        this.plan[0]['title']='联合培养方案制定';
        this.plan[0]['url']='https://docs.qq.com/doc/DYVhXRkZlRXB2TkhV';
        this.plan[1]['title']='联合设计课程体系和规划制定教学内容';
        this.plan[1]['url']='https://docs.qq.com/doc/DYVROdGR0emx0cUFu';
        this.plan[2]['title']='联合制定培养质量标准';
        this.plan[2]['url']='https://docs.qq.com/doc/DYUxDVWFKaWtlZXV0';

        // let that=this;
        // that.$http.post('/practice/evaluate/gettable2',{
        //   page:1,
        //   username:that.user
        // }).then(function (res) {
        //   console.log(res.data)
        //   that.surveyList=res.data.data[0];
        //   that.ansNum=res.data.data[1];
        //   that.totalpage=res.data.data[2];
        //   let curTime = Date.now();
        //   that.surveyList.forEach((item) => {
        //     if (item.status == 2) {
        //       that.itemTime = new Date(item.endTime.replace(/-/g, ','))*1;
        //       if (that.itemTime < curTime) {
        //         item.status = 3;
        //       }
        //     }
        //   })
        // })
      },

      showPrompt(text) {
        this.promptText = text;
        this.isShowPrompt = true;
      }
    },

    computed: {
      isCheckedAll() {
        return this.quList.every( item => item.checked);
      }
    },
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
