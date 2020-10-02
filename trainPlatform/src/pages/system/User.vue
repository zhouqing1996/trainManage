<template>
  <div class="display1">
    <button class="btn1 active">用户管理</button>
    <div class="display2">
      <div class="col-md-12">
        <div>
          <div class="user">
            <span style="color:#fff;">搜索用户：</span>
            <select v-model="searchKind">
              <option value="1">用户名</option>
              <option value="2">姓名</option>
              <option value="3">性别</option>
              <option value="4">年龄</option>
              <option value="5">身份证号</option>
              <option value="6">省份</option>
              <option value="7">城市</option>
              <option value="8">单位</option>
              <option value="9">职业</option>
              <option value="10">邮箱</option>
              <option value="0">全部</option>
            </select>
            <input v-model="searchKey" placeholder="输入用户名称" style="font-size:14px;width:300px;font-weight:lighter">
          </div>
          <button class="btn3 icon-sousuo" v-on:click="searchuserlist()">
            搜索
          </button>
          <button type="button" class="btn4 icon-daochu" id="export-table" v-on:click="export2Excel()">导出
          </button>
          <router-view/>
          <table id="userStatistics">
            <tr>
              <th>用户名</th>
              <th>姓名</th>
              <th>性别
                <i v-show="sortType=='up'&&sortKind=='1'" class="sort icon-paixushengxu" v-on:click="sortdown(1)"></i>
                <i v-show="sortType!='up'||sortKind!='1'" class="sort icon-paixujiangxu" v-on:click="sortup(1)"></i>
              </th>
              <th>年龄
                <i v-show="sortType=='up'&&sortKind=='2'" class="sort icon-paixushengxu" v-on:click="sortdown(2)"></i>
                <i v-show="sortType!='up'||sortKind!='2'" class="sort icon-paixujiangxu" v-on:click="sortup(2)"></i>
              </th>
              <th>身份证号
              </th>
              <th>
                省份
                <i v-show="sortType=='up'&&sortKind=='3'" class="sort icon-paixushengxu" v-on:click="sortdown(3)"></i>
                <i v-show="sortType!='up'||sortKind!='3'" class="sort icon-paixujiangxu" v-on:click="sortup(3)"></i>
              </th>
              <th>
                城市
                <i v-show="sortType=='up'&&sortKind=='4'" class="sort icon-paixushengxu" v-on:click="sortdown(4)"></i>
                <i v-show="sortType!='up'||sortKind!='4'" class="sort icon-paixujiangxu" v-on:click="sortup(4)"></i>
              </th>
              <th>
                单位
                <i v-show="sortType=='up'&&sortKind=='5'" class="sort icon-paixushengxu" v-on:click="sortdown(5)"></i>
                <i v-show="sortType!='up'||sortKind!='5'" class="sort icon-paixujiangxu" v-on:click="sortup(5)"></i>
              </th>
              <th>
                职业
                <i v-show="sortType=='up'&&sortKind=='6'" class="sort icon-paixushengxu" v-on:click="sortdown(6)"></i>
                <i v-show="sortType!='up'||sortKind!='6'" class="sort icon-paixujiangxu" v-on:click="sortup(6)"></i>
              </th>
              <th>邮箱</th>
              <th>删除用户</th>
            </tr>
            <tr v-for="user in userList">
              <td>{{user.USER}}</td>
              <td>{{user.NAME}}</td>
              <td>{{user.SEX}}</td>
              <td>{{user.AGE}}</td>
              <td>{{user.CARDID}}</td>
              <td>{{user.PROVINCE}}</td>
              <td>{{user.CITY}}</td>
              <td>{{user.SCHOOL}}</td>
              <td>{{user.RANK}}</td>
              <td>{{user.EMAIL}}</td>
              <td>
                <span v-on:click="deletechange(user.USER)"><i class="delete icon-changyonggoupiaorenshanchu"></i></span>
              </td>
            </tr>
          </table>
          <div class="page">
            <ul class="pagination pagination-sm"><!--分页-->
              <li class="page-item" v-if="currentpage!=1">
                <a class="page-link" href="#" v-on:click="prepage()">上一页</a>
              </li>
              <li class="page-item" v-for="index in pagenums" v-bind:class="{ active: currentpage == index} ">
                <a class="page-link" href="#" v-on:click="pageChange(index)">{{index}}</a>
              </li>
              <li class="page-item" v-if="currentpage!=totlepage"><a class="page-link" href="#" v-on:click="nextpage()">下一页</a>
              </li>
              <li class="page-item"><a class="page-link" href="#">共<i>{{totlepage}}</i>页</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    name: 'user',
    data() {
      return {
        //用户列表
        userList: [],
        //导出数据
        excelData: [],
        //翻页相关
        currentpage: 1,//当前页
        totlepage: 10,//总页数
        visiblepage: 5,//可见页数

        sortKind:0,
        sortType:'up',

        searchKind:0,//检索类别
        searchKey:""//检索关键词
      }

    },
    methods: {
      //所有查找用户的请求
      getUserInfor:function(){
        this.$http.get('/system/system/getuserdata')//代替http://localhost:3000/list
          .then((res) => {
            this.userList = res.data.data.pageall;
            this.currentpage = res.data.data.page;
            this.totlepage = res.data.data.totlepage;
            console.log(res.data);
          }, (err) => {
            console.log(err);
          })
      },
      sortup(i){
        this.sortType='up';
        this.sortKind=i;
        this.searchUserInfo()
      },
      sortdown(i){
        this.sortType='down';
        this.sortKind=i;
        this.searchUserInfo()
      },
      searchUserInfo:function(){
        let fd = new FormData();
        if(this.searchKind==1)
          fd.append('USER', this.searchKey);
        else if(this.searchKind==2)
          fd.append('NAME', this.searchKey);
        else if(this.searchKind==3)
          fd.append('SEX', this.searchKey);
        else if(this.searchKind==4)
          fd.append('AGE', this.searchKey);
        else if(this.searchKind==5)
          fd.append('CARDID', this.searchKey);
        else if(this.searchKind==6)
          fd.append('PROVINCE', this.searchKey);
        else if(this.searchKind==7)
          fd.append('CITY', this.searchKey);
        else if(this.searchKind==8)
          fd.append('SCHOOL', this.searchKey);
        else if(this.searchKind==9)
          fd.append('RANK', this.searchKey);
        else if(this.searchKind==10)
          fd.append('EMAIL', this.searchKey);
        else{

        }

        switch (this.sortKind) {
          case 1:
            fd.append('kind', 'SEX');
            break;
          case 2:
            fd.append('kind', 'AGE');
            break;
          case 3:
            fd.append('kind', 'PROVINCE');
            break;
          case 4:
            fd.append('kind', 'RANK');
            break;
          case 5:
            fd.append('kind', 'SCHOOL');
            break;
          case 6:
            fd.append('kind', 'RANK');
            break;
          default:
            fd.append('kind', 'ID');
            break;
        }

        if(this.sortType=='up')
          fd.append('type', 'SORT_ASC');
        else
          fd.append('type', 'SORT_DESC');

        this.$http.post('/system/system/querydata', fd).then(body => {
          this.userList = body.data.data.pageall;
          this.currentpage = body.data.data.page;
          this.totlepage = body.data.data.totlepage;
          console.log(this.userList);
        })
      },
      deletechange: function (a) {
        console.log(a);
        this.axios.get("/system/system/deleteuserdata?USER=" + a).then(function (res) {
          console.log(res.data);
          if (res.data == 1) {
            location.reload();
          }
        })
          .catch(function (error) {
            console.log(error);
          });
      },
      searchuserlist: function () {
        this.searchUserInfo()
      },

      pageChange: function (page) {//分页
        if (this.currentpage != page) {
          this.currentpage = page;
        }
        this.searchUserInfo()
      },
      prepage: function (page) {//上一页
        page--;
        if (this.currentpage != page) {
          this.currentpage = page;
        }
        this.searchUserInfo()
      },
      nextpage: function (page) { //下一页
        page++;
        if (this.currentpage != page) {
          this.currentpage = page;
        }
        this.searchUserInfo()
      },
      export2Excel:function() {
        this.$http.get('/system/system/exportuserdata').then((res) => {
          this.excelData = res.data.data.pageall;
          console.log(this.excelData);
          require.ensure([], () => {
            const {export_json_to_excel} = require('@/excel/Export2Excel.js');//引入文件
            const tHeader = ['序号','用户名', '姓名', '性别', '年龄', '身份证号', '省份','城市','单位','职业','邮箱'];
            // 上面设置Excel的表格第一行的标题
            const filterVal = ['ID','USER', 'NAME', 'SEX', 'AGE', 'CARDID', 'PROVINCE', 'CITY', 'SCHOOL', 'RANK', 'EMAIL'];
            // 上面的index、phone_Num、school_Name是tableData里对象的属性
            const list = this.excelData;  //把data里的tableData存到list
            console.log(list);
            const data = this.formatJson(filterVal, list);
            export_json_to_excel(tHeader, data, '用户表单');
          })
        })//传数据
      },
      formatJson(filterVal, jsonData) {
        //console.log(jsonData);
        return jsonData.map(v => filterVal.map(j => v[j]));
      }
    },
    computed: {
      //计算属性：返回页码数组，这里会自动进行脏检查，不用$watch();
      pagenums: function () {//分页
        //初始化前后页边界
        let lowPage = 1;
        let highPage = this.totlepage;
        let pageArr = [];
        if (this.totlepage > this.visiblepage) {//总页数超过可见页数时，进一步处理；
          let subVisiblePage = Math.ceil(this.visiblepage / 2);
          if (this.currentpage > subVisiblePage && this.currentpage < this.totlepage - subVisiblePage + 1) {//处理正常的分页
            lowPage = this.currentpage - subVisiblePage;
            highPage = this.currentpage + subVisiblePage - 1;
          }
          else if (this.currentpage <= subVisiblePage) {//处理前几页的逻辑
            lowPage = 1;
            highPage = this.visiblepage;
          }
          else {
            //处理后几页的逻辑
            lowPage = this.totlepage - this.visiblepage + 1;
            highPage = this.totlepage;
          }
        }
        //确定了上下page边界后，要准备压入数组中了
        while (lowPage <= highPage) {
          pageArr.push(lowPage);
          lowPage++;
        }
        return pageArr;

      }
    },
    created: function () {
      this.getUserInfor();
    },
  }
</script>

<style scoped>
  .display1 {
    /* padding-left:20px;*/
    padding-top: 10px;
  }
  .sort{
    cursor: pointer;
  }
  .display2 {
    border: solid 2px #e0e0e0;
    height: 600px;
    /*text-align: center;*/
    width: 98%;
    padding-left: 10px;
    padding-right: 10px;
    background-color: #fff;
  }

  .user {
    float: left;
    margin: 10px 0 10px 0;
    font-weight: bold;
    background-color: #00AAFF;
    border: solid 1px #00AAFF;
    border-radius: 5px;
    width: 40%;
    padding: 5px;
  }

  .sort {
    color: #1C93FC;
  }

  .sort:hover {
    color: #5CB0FA;
  }

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
  }

  table, td {
    border: solid 1px #ccc;
    padding: 5px;
    text-align: center;
    font-size: 14px;
  }

  .btn1 {
    font-size: 18px;
    padding: 10px 10px;
    border-top-left-radius: 3px;
    border-top-right-radius: 3px;
    border: 1px solid #ccc;
    cursor: pointer;
    background: #f0f0f0;
    margin-bottom: -1px;
    color: #000;
    /*margin-right: 0px;*/
  }

  .btn1:hover {
    background: #e0e0e0;
  }

  .active {
    background: #e0e0e0;
  }

  .btn2 {
    margin-left: 0px;
  }

  .input {
    width: 80px;
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
    margin-left: 15px;
    margin-top: 13px;
    /*margin-bottom: 5px;*/
  }

  /* .btn3:hover{
     background-color:#5FA7FE;
   }*/
  .btn4 {
    width: 90px;
    padding: 7px;
    font-size: 14px;
    border-radius: 3px;
    border: none;
    color: white;
    background-color: #FA4E28;
    float: right;
    /*margin-left: 15px;*/
    margin-top: 20px;
    /*margin-bottom: 5px;*/
  }

  .btn4:hover {
    background-color: #FC6F4F;
  }

  .delete:hover {
    color: #C1C1C2
  }

  .page {
    text-align: center;
  }

  .top {
    background: #e0e0e0;
  }

  .el-date-editor--datetimerange.el-input, .el-date-editor--datetimerange.el-input__inner {
    width: 360px !important;
  }
</style>
