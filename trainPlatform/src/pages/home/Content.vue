<template>
  <div style=" margin-top:20px;" v-if="this.power.userkind==1">
    <div class="meauPanel" v-for="item in judgeClick" :key="item.id" v-show="item.isVisit">
      <router-link :to="{ path:item.path , query:{ name:item.name }}" >
<!--        <router-link :to="{ path:item.path , query:{ name:item.name }}" @click.native="flushCom" >-->
        <div>
          <span v-show="kind1==1">
              <i v-if="item.id==1" class="iconxuexiaogaikuang" style="font-size: 60px"></i>
              <i v-if="item.id==2" class="icongongsi" style="font-size: 70px"></i>
              <i v-if="item.id==3" class="iconbiyeshixi" style="font-size: 60px"></i>
<!--              <i v-if="item.id==4" class="el-icon-location" style="font-size: 60px"></i>-->
<!--              <i v-if="item.id==5" class="iel-icon-s-open" style="font-size: 60px"></i>-->
              <i v-if="item.id==6" class="icongongneng_fabuxuqiu" style="font-size: 55px"></i>
              <i v-if="item.id==7" class="icontiaochawenjuan1" style="font-size: 50px"></i>
            <!--<i v-if="item.id==8" class="icontongji4" style="font-size: 60px"></i>-->
              <i v-if="item.id==9" class="iconxitong" style="font-size: 60px"></i>
          </span>
          <span >{{ item.name }}</span>
        </div>
      </router-link>
    </div>
    <div class="clean"></div>
  </div>
</template>

<script>
  export default {
    data () {
      return {
        userList: [
          {
            'id': 1,
            'name': '院校管理',
            'path': '/department',
            'num': '10'
          },
          {
            'id': 2,
            'name': '企业管理',
            'path': '/company',
            'num': '20'
          },
          {
            'id': 3,
            'name': '顶岗实习',
            'path': '/practice',
            'num': '30'
          },
          // {
          //   'id': 4,
          //   'name': '跟岗实习',
          //   'path': '/gengangpractice',
          //   'num': '40'
          // },
          // {
          //   'id': 5,
          //   'name': '认识实习',
          //   'path': '/jianxi',
          //   'num': '50'
          // },
          {
            'id': 6,
            'name': '校企需求互通',
            'path': '/requirement',
            'num': '60'
          },
          {
            'id': 7,
            'name': '毕业生追踪调查',
            'path': '/tracking',
            'num': '70'
          },
          // {
          //   "id": 8,
          //   "name": "现代学徒制",
          //   "path": "/apprenticeship",
          //   "num": "80"
          // },
          {
            'id': 9,
            'name': '权限管理',
            'path': '/system/power',
            'num': '90'
          }
        ],
        power: this.$store.getters.showPower,
        module: this.$store.getters.showModule,
        kind1: this.$store.getters.getUserKind
      }
    },
    methods: {
      alertMsg () {
        alert('无权限查看')
      }
    },
      created () {
          console.log(this.kind1)
      },
    computed: {
      judgeClick: function () {
        console.log(this.power)
        for (let item in this.userList) {
          switch (this.userList[item].id) {
            case 1:
              if (this.power != null && (this.power.super == '1' || this.power.major == '1')) {
                this.userList[item].isVisit = true
                if (this.power.super == '1') {
                  this.userList[item].path = '/department/major/major'
                } else {
                  this.userList[item].path = '/department/major/majorinfo'
                }
              } else {
                this.userList[item].isVisit = false
              }
              break
            case 2:
              if (this.power != null && (this.power.super == '1' || this.power.company == '1')) {
                this.userList[item].isVisit = true
                if (this.power.super == '1') {
                  this.userList[item].path = '/company'
                } else {
                  this.userList[item].path = '/company/Acompany'
                }
              } else { this.userList[item].isVisit = false }
              break
            case 3:
              if (this.power != null && (this.power.super == '1' || this.power.practice == '1')) {
                this.userList[item].isVisit = true
                if (this.power.userkind == '1') {
                  this.userList[item].path = '/practice/teacher'
                } else {
                  this.userList[item].path = '/practice/company'
                }
              } else { this.userList[item].isVisit = false }
              break
            case 4:
              if (this.power != null && (this.power.super == '1' || this.power.practice == '1')) {
                this.userList[item].isVisit = true
                if (this.power.userkind == '1') {
                  this.userList[item].path = '/gengangpractice/teacher'
                } else {
                  this.userList[item].path = '/gengangpractice/company'
                }
              } else { this.userList[item].isVisit = false }
              break
            case 5:
              if (this.power != null && (this.power.super == '1' || this.power.practice == '1')) {
                this.userList[item].isVisit = true
                if (this.power.userkind == '1') {
                  this.userList[item].path = '/jianxi/teacher'
                } else {
                  this.userList[item].path = '/jianxi/company'
                }
              } else { this.userList[item].isVisit = false }
              break
            case 6:
              if (this.power != null && (this.power.super == '1' || this.power.requirement == '1')) {
                this.userList[item].isVisit = true
                this.userList[item].path = '/requirement/surveylist'
              } else { this.userList[item].isVisit = false }
              break
            case 7:
              if (this.power != null && (this.power.super == '1' || this.power.tracking == '1')) {
                this.userList[item].isVisit = true
                this.userList[item].path = '/tracking/tracksurvey'
              } else { this.userList[item].isVisit = false }
              break
            case 8:
              if (this.power != null && (this.power.super == '1' || this.power.statistics == '1')) {
                this.userList[item].isVisit = true
                if (this.power.userkind == '1') {
                  this.userList[item].path = '/apprenticeship/enroll/demandlist'
                } else {
                  this.userList[item].path = '/apprenticeship/enroll/demandlistcom'
                }
              } else { this.userList[item].isVisit = false }
              break
            case 9:
              if (this.power != null && (this.power.super == '1' || this.power.system == '1')) {
                this.userList[item].isVisit = true
                this.userList[item].path = '/system/power'
              } else { this.userList[item].isVisit = false }
              break
            /* case 8:
              if(this.power!=null&&(this.power.super=="1"||this.power.system=="1"))
                this.userList[item].isVisit=true;
              else
                this.userList[item].isVisit=false;
              break; */
            default:
              break
          }
        }
        console.log(this.userList)
        return this.userList
      }
    }
    // 方法中可以设置数据处理和数据的获取等等
    // methods: {
    //   getUser () {
    //     this.$http.get('/api/list')//代替http://localhost:3000/list
    //       .then((res) => {
    //         console.log(res.data)
    //         this.userList = res.data
    //       }, (err) => {
    //         console.log(err)
    //       })
    //   }
    // },
    // created(){
    //   this.getUser();
    // }
  }
</script>

<style scoped>
  .meauPanel{
    text-align: center;
    line-height: 260px;
    width: 250px;
    height: 230px;
    float: left;
    border: 1px solid #ECECEC;
    background:#FFF;
    /*border:1px solid #fff;*/
    border-radius: 10px;
    font-size: 18px;
    margin: 10px;
  }
</style>
