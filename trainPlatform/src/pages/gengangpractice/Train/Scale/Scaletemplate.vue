<template>
  <div>
    <div class="container" v-if="visible == 0">
      <el-button style="margin-left: 40px" type="warning" @click="create">+ 新建量表模板</el-button>
      <el-button style="margin-left: 10px" @click="backto">返回</el-button>
      <div v-if="surveyList1" v-for="(item,index) in surveyList1" :key="index">
        <el-card class="box-card">
          <div slot="header" class="clearfix">
            <span >{{item.title}}</span>
            <span style="margin-left: 10px;">ID:{{item.id}}</span>
          </div>
          <div class="text item">
            <router-link to="">
              <el-button plain size="mini" @click="view(item.id)"><i  class="iconchakan"></i>查看</el-button>
            </router-link>
            <el-button @click="iterator = editItem(item); iterator.next()" plain size="mini" v-show="item.status==1"><i  class="iconbianji1"></i>编辑</el-button>
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
    <div><Templateadd v-if="visible == 1" :id="id" :flag="flag" :row="row" v-on:listenToTemp="listenToTemp"></Templateadd></div>
    <div><Templateview v-if="visible == 2" :id="id2" :flag="flag2" v-on:listenToView="listenToView"></Templateview></div>
    <div><Templateedit v-if="visible == 3" :id="id3" :flag="flag" v-on:listenToEdit="listenToEdit"></Templateedit></div>
  </div>
</template>

<script>
  import Templateadd from './Templateadd'
  import Templateview from './Templateview'
  import Templateedit from './Templateedit'
  export default {
    name: 'Scaletemplate',
    props: ['id', 'row'],
    components: {
      Templateadd,
      Templateview,
      Templateedit
    },
    data () {
      return {
        quData: {},
        surveyList1: [],
        ansNum: [],
        quList: [],
        iterator: {},
        totalpage: '',
        isShowPrompt: false,
        isShowPrompt1: false,
        templateType: '',
        promptText: '',
        address: '',
        flag: 21,
        visible: 0,
        id2: -1,
        id3: -1,
        flag2: 20
      }
    },

    created () {
      this.user = this.$store.state.mutations.user
      this.getdata()
    },

    methods: {
      getdata: function () {
        let that = this
        this.$http.get('/requirement/template/gettemplate2').then(function (res) {
          console.log(res.data)
          if (res.data.data == false) {
          } else {
            that.surveyList1 = res.data.data[0]
          }
        })
      },

      handleOpen (key, keyPath) {
        console.log(key, keyPath)
      },
      handleClose (key, keyPath) {
        console.log(key, keyPath)
      },

      templateShow: function (type) {
        console.log(type)
        let that = this
        this.$http.post('/requirement/require/templateshow', {
          page: 1,
          type: type
        }).then(function (res) {
          console.log(res.data)
          that.surveyList = res.data.data
        })
      },

      showPrompt (text) {
        this.promptText = text
        this.isShowPrompt = true
      },

      showPrompt1 (item) {
        this.quData = item
        this.isShowPrompt1 = true
      },

      editClass (templateType) {
        console.log(templateType)
        this.quData.type = templateType
        console.log(this.quData)
        this.$http.post('/requirement/require/editclass', {
          quData: this.quData
        }).then((res) => {
          console.log(res.data)
          if (res.data.message == 1) {
            alert('模板修改成功！')
            this.$router.push({ path: '/requirement/templatelist'})
          } else {
            alert('模板修改失败！')
          }
        })
        this.isShowPrompt1 = false
      },

      *deleteItem (item) {
        console.log(item)
        yield this.showPrompt(`确认要删除《${item.title}》？`)
        let that = this
        this.$http.post('/requirement/require/deletesurvey', {id: item.id}).then(function (res) {
          console.log(res.data)
          if (res.data.message == 0) {
            alert('问卷删除成功！')
            that.getdata()
          }
        })
      },

      *deleteCheckedItems () {
        let checkedList = this.quList.filter(item => item.checked)
        if (!checkedList.length) {
          return
        }
        yield this.showPrompt('确认要删除所选问卷？')
        yield this.quList = this.quList.filter(item => !item.checked)
      },

      *editItem (item) {
        yield this.showPrompt(`确认要编辑《${item.title}》？`)
        this.id3 = item.id
        this.visible = 3
      },

      create () {
        this.visible = 1
      },

      backto () {
        this.$emit('listenToTemp', 'back')
      },

      listenToTemp (param) {
        if (param == 'back') {
          this.visible = 0
        } else {
          this.visible = 0
          this.getdata()
        }
      },

      listenToView (param) {
        if (param == 'back') {
          this.visible = 0
        } else {
          this.visible = 0
          this.getdata()
        }
      },

      listenToEdit (param) {
        if (param == 'back') {
          this.visible = 0
        } else {
          this.visible = 0
          this.getdata()
        }
      },

      view (id) {
        this.id2 = id
        this.visible = 2
      }
    }
  }
</script>

<style scoped lang="scss">
  @import '../../../../style/public.scss';

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
