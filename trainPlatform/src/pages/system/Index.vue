
<template>
  <div class="display1">
  <button class="btn1 "  v-bind:class="{ active: isActive }">用户管理</button>
  <!--<button class="btn3 icon-sousuo">搜索</button>-->
  <div class="display2">
  <!--<div class="col-md-12">-->
        <!--<button class="btn3 icon-sousuo">搜索</button>-->
      <!--</div>-->
      <div class="col-md-12">
        <div>
                <div class="user" >
                <span style="color:#fff;">搜索用户：</span>
                <input  v-model="inputuser" placeholder="输入用户名称" style="font-size:14px;width:300px;font-weight:lighter">
              </div>

              <button class="btn3 icon-sousuo" v-on:click="searcha(inputname,sexselected,inputage,inputCARDID,inputremarks,value3)">搜索</button>

              <button type="button" class="btn4 icon-daochu" id="export-table" v-on:click="export2Excel(inputname,sexselected,inputage,inputCARDID,inputremarks,value3)">导出</button>
               <router-view/>
   <table id="userStatistics">
                <tr>
                  <th>ID</th>
                  <th>姓名
                    <input class="input1" v-model="inputname" placeholder="搜索姓名" style="font-size:14px;">
                    <!--<p>{{inputname}}</p>//测试-->
                  </th>
                  <th>性别
                    <select v-model="sexselected"  style="font-size:14px;">
                      <option disabled value="">选择</option>
                      <option value="1" >男</option>
                      <option value="2">女</option>
                      <option value="null">空</option>
                    </select>
                  </th>
                 <th>年龄
                    <input class="input1" v-model="inputage" placeholder="输入年龄" style="font-size:14px;">
                    <!--<p>{{inputage}}</p>//测试-->
                    <i v-show="agesort" class="sort icon-paixushengxu" v-on:click="ageup(inputname,sexselected,inputage,inputCARDID,inputremarks,value3)"></i>
                    <i v-show="!agesort" class="sort icon-paixujiangxu" v-on:click="agedown(inputname,sexselected,inputage,inputCARDID,inputremarks,value3)"></i>
                  </th>
                  <th>手机号
                    <input class="input1" v-model="inputCARDID" placeholder="输入手机号" style="font-size:14px;width:120px">
                    <!--<p>{{inputsex}}</p>//测试-->
                  </th>
                  <th>备注</th>
                  <th>删除用户</th>
                </tr>
                <tr v-for="user in userList">
                  <td>{{user.ID}}</td>
                  <td>{{user.NAME}}</td>
                  <td>{{user.SEX}}</td>
                  <td>{{user.AGE}}</td>
                  <td>{{user.CARDID}}</td>
                   <td>{{user.REMARKS}}</td>
                    <td>
                    <span v-on:click="deletechange(user.USER)" ><i class="delete icon-changyonggoupiaorenshanchu" ></i></span>
                  </td>
                </tr>
              </table>
              <div class="page">
                  <ul class="pagination pagination-sm"><!--分页-->
                     <li class="page-item" v-if="currentpage!=1"><a class="page-link" href="#" v-on:click="prepage(currentpage,inputname,sexselected,inputage,inputCARDID,inputremarks,value3)">上一页</a></li>
                     <li class="page-item" v-for="index in pagenums" v-bind:class="{ active: currentpage == index} ">
                        <a class="page-link" href="#" v-on:click="pageChange(index,inputname,sexselected,inputage,inputCARDID,inputremarks,value3)">{{index}}</a>
                     </li>
                     <li class="page-item" v-if="currentpage!=totlepage"><a class="page-link"  href="#"  v-on:click="nextpage(currentpage,inputname,sexselected,inputage,inputCARDID,inputremarks,value3)">下一页</a></li>
                     <li class="page-item"><a class="page-link"  href="#">共<i>{{totlepage}}</i>页</a></li>
                  </ul>
              </div>
 </div>
 </div>
 </div>
 </div>
</template>

<script>
var moment = require('moment');
    let nowTime=moment().format();//当前时间
    console.log(nowTime);
    console.log(this.starTime);
    let start=this.starTime;
    // start=start.toString();
    console.log(start);

    // import FileSaver from 'file-saver';
  //import XLSX from 'xlsx';
  export default {
  	name: "user",
  data() {
        return {
          userList:[
            {
              ID: 1,
              NAME: '张三',
              SEX: '男',
              AGE: '18',
              CARDID:'110',
              REMARKS:'NULL'
            },
            {
              ID: 2,
              NAME: '李四',
              SEX: '男',
              AGE: '20',
              CARDID:'119',
              REMARKS:'NULL'
            },
            {
              ID: 3,
              NAME: '王二麻子',
              SEX: '男',
              AGE: '28',
              CARDID:'114',
              REMARKS:'NULL'
            },
            {
              ID: 4,
              NAME: '王二麻子',
              SEX: '男',
              AGE: '28',
              CARDID:'114',
              REMARKS:'NULL'
            },
             {
              ID: 5,
              NAME: '王二麻子',
              SEX: '男',
              AGE: '28',
              CARDID:'114',
              REMARKS:'NULL'
            },
             {
              ID: 6,
              NAME: '王二麻子',
              SEX: '男',
              AGE: '28',
              CARDID:'114',
              REMARKS:'NULL'
            },
             {
              ID: 7,
              NAME: '王二麻子',
              SEX: '男',
              AGE: '28',
              CARDID:'114',
              REMARKS:'NULL'
            },
             {
              ID: 8,
              NAME: '王二麻子',
              SEX: '男',
              AGE: '28',
              CARDID:'114',
              REMARKS:'NULL'
            },
             {
              ID: 9,
              NAME: '王二麻子',
              SEX: '男',
              AGE: '28',
              CARDID:'114',
              REMARKS:'NULL'
            },
             {
              ID: 10,
              NAME: '王二麻子',
              SEX: '男',
              AGE: '28',
              CARDID:'114',
              REMARKS:'NULL'
            },
            {
              ID: 11,
              NAME: '王二麻子',
              SEX: '男',
              AGE: '28',
              CARDID:'114',
              REMARKS:'NULL'
            }
          ],
             content: "",//传值测试
          currentpage: 1,//当前页
          datesort: true,//日期排序
          endTime: '',
          excelData:'',//导出数据
          inputuser: '',//输入用户名称
          inputname: '',//输入姓名
          inputsex: '',//输入性别
          inputage: '',//输入年龄
          inputCARDID: '',//输入手机号
          inputremarks: '',//输入备注
          isActive: true,
          agesort: true,//金额排序
          sexselected: '',//支付下拉列表
          showage: true,
         // showinvoice: false,
          showFirstText: true,//显示上一页
          showNextText: true,//显示下一页
          startTime: '',
          totlepage: 10,//总页数
          type: 1,//排序类型，默认日期降序，2为日期升序，3为金额降序，4为金额升序
          visiblepage: 10,//可见页数
          value3: '',//排序类型，默认日期降序，2为日期升序
       }
       },

           components: {
      },

       methods: {
       	getUser () {
        this.$http.get('/system/list')//代替http://localhost:3000/list
        .then((res) => {
          console.log(res.data)
          this.userList = res.data
        }, (err) => {
          console.log(err)
        })
      },
       deletechange: function (a) {
          console.log(a);
          this.axios.get("/system/system/deletedata?USER="+a).then(function(res){
            console.log(res.data);
            if(res.data==1){
               location.reload();
            }
          })
          .catch(function (error) {
            console.log(error);
          });
        },
        searcha: function (a1, a2, a3,a4, a5) {
          if(a5.length!=0){
            this.startTime=a5[0];
            this.endTime=a5[1];
          }
          console.log(this.startTime);
          let fd = new FormData();
          fd.append('NAME', a1);//传姓名
          fd.append('SEX', a2);//传性别
          fd.append('AGE', a3);//传年龄
          fd.append('CARDID', a4);//传手机号
          fd.append('REMARKS', a5);//传备注
          //fd.append('ACCOUNT_MODE', a5);//传收款方式
          //fd.append('STARTDATE', this.startTime);//传开始时间
          //fd.append('ENDDATE', this.endTime);//传结束时间
          fd.append('page', this.currentpage);//传当前页
          fd.append('type',this.type);//传类型
          this.$http.post('/system/system/querydata', fd).then(body => {
            this.userList = body.data.data.pageall;
            this.totlepage = body.data.data.totlepage;
            console.log(this.userList);
          })
        },
         ageup:function(a1, a2, a3, a4, a5){//年龄升序
          this.type=4;
          this.agesort=!this.agesort;
          if(a5.length!=0){
            this.startTime=a5[0];
            this.endTime=a5[1];
          }
          console.log(this.startTime);
          let fd = new FormData();
          fd.append('NAME', a1);//传姓名
          fd.append('SEX', a2);//传性别
          fd.append('AGE', a3);//传年龄
          fd.append('CARDID', a4);//传手机号
          fd.append('REMARKS', a5);//传备注
          //fd.append('CONFERENCEID', a4);//传会议ID
          //fd.append('ACCOUNT_MODE', a5);//传收款方式
          //fd.append('STARTDATE', this.startTime);//传开始时间
          //fd.append('ENDDATE', this.endTime);//传结束时间
          fd.append('page', this.currentpage);//传当前页
          fd.append('type',this.type);//传类型
          console.log(fd.getAll('type'));
          this.$http.post('/system/system/querydata', fd).then(body => {
            this.userList = body.data.data.pageall;
            this.totlepage = body.data.data.totlepage;
            console.log(this.userList);
          })
        },
          agedown:function(a1, a2, a3,a4, a5){//金额降序
          this.type=3;
          this.agesort=!this.agesort;
          console.log(this.agesort);
          if(a5.length!=0){
            this.startTime=a5[0];
            this.endTime=a5[1];
          }
          console.log(this.startTime);
          let fd = new FormData();
          fd.append('NAME', a1);//传姓名
          fd.append('SEX', a2);//传性别
          fd.append('AGE', a3);//传年龄
          fd.append('CARDID', a4);//传手机号
          fd.append('REMARKS', a5);//传备注
          //fd.append('CONFERENCEID', a4);//传会议ID
          //fd.append('ACCOUNT_MODE', a5);//传收款方式
          //fd.append('STARTDATE', this.startTime);//传开始时间
          //fd.append('ENDDATE', this.endTime);//传结束时间
          fd.append('page', this.currentpage);//传当前页
          fd.append('type',this.type);//传类型
          console.log(fd.getAll('type'));
          this.$http.post('/system/system/querydata', fd).then(body => {
            this.userList = body.data.data.pageall;
            this.totlepage = body.data.data.totlepage;
            console.log(this.userList);
          })
        },

        pageChange: function(page,a1, a2, a3,a4, a5){//分页
          if (this.currentpage != page) {
            this.currentpage = page;
            // this.$dispatch('page-change', page); //父子组件间的通信：==>子组件通过$diapatch(),分发事件，父组件冒泡通过v-on:page-change监听到相应的事件；
          }
          console.log(page);
          if(a5.length!=0){
            this.startTime=a5[0];
            this.endTime=a5[1];
          }
          console.log(this.startTime);
          let fd = new FormData();
          fd.append('NAME', a1);//传姓名
          fd.append('SEX', a2);//传性别
          fd.append('AGE', a3);//传年龄
          fd.append('CARDID', a4);//传手机号
          fd.append('REMARKS', a5);
          //fd.append('CONFERENCEID', a4);//传会议ID
          //fd.append('ACCOUNT_MODE', a5);//传收款方式
          //fd.append('STARTDATE', this.startTime);//传开始时间
          //fd.append('ENDDATE', this.endTime);//传结束时间
          fd.append('page', this.currentpage);//传当前页
          fd.append('type',this.type);//传类型
          console.log(fd.getAll('NAME'));
          console.log(fd.getAll('ENDDATE'));
          this.$http.post('/system/system/querydata', fd).then(body => {
            this.userList = body.data.data.pageall;
            this.totlepage = body.data.data.totlepage;
            console.log(this.userList);
          })
        },

         prepage:function(page,a1, a2, a3,a4, a5){//上一页
          page--;
          if (this.currentpage != page) {
            this.currentpage = page;
            // this.$dispatch('page-change', page); //父子组件间的通信：==>子组件通过$diapatch(),分发事件，父组件冒泡通过v-on:page-change监听到相应的事件；
          }
          console.log(page);
          if(a5.length!=0){
            this.startTime=a5[0];
            this.endTime=a5[1];
          }
          console.log(this.startTime);
          let fd = new FormData();
          fd.append('NAME', a1);//传姓名
          fd.append('SEX', a2);//传性别
          fd.append('AGE', a3);//传年龄
          fd.append('CARDID', a4);//传手机号
          fd.append('REMARKS', a5);//传备注
          //fd.append('CONFERENCEID', a4);//传会议ID
          //fd.append('ACCOUNT_MODE', a5);//传收款方式
          //fd.append('STARTDATE', this.startTime);//传开始时间
          //fd.append('ENDDATE', this.endTime);//传结束时间
          fd.append('page', this.currentpage);//传当前页
          fd.append('type',this.type);//传类型
          // console.log(fd.getAll('NAME'));
          // console.log(fd.getAll('ENDDATE'));
          this.$http.post('/system/system/querydata', fd).then(body => {
            this.userList = body.data.data.pageall;
            this.totlepage = body.data.data.totlepage;
            console.log(this.userList);
          })
        },

        nextpage:function(page,a1, a2, a3,a4, a5){//下一页
          console.log(a1);
          console.log(a2);
          console.log(a3);
          console.log(a4);
          console.log(a5);
          page++;
          if (this.currentpage != page) {
            this.currentpage = page;
            // this.$dispatch('page-change', page); //父子组件间的通信：==>子组件通过$diapatch(),分发事件，父组件冒泡通过v-on:page-change监听到相应的事件；
          }
          console.log(page);
          if(a5.length!=0){
            this.startTime=a5[0];
            this.endTime=a5[1];
          }
          // console.log(this.startTime);
          let fd = new FormData();
          fd.append('NAME', a1);//传姓名
          fd.append('SEX', a2);//传性别
          fd.append('AGE', a3);//传年龄
          fd.append('CARDID', a4);//传手机号
          fd.append('REMARKS', a5);
          //fd.append('CONFERENCEID', a4);//传会议ID
          //fd.append('ACCOUNT_MODE', a5);//传收款方式
          //fd.append('STARTDATE', this.startTime);//传开始时间
          //fd.append('ENDDATE', this.endTime);//传结束时间
          fd.append('page', this.currentpage);//传当前页
          fd.append('type',this.type);//传类型
          console.log(fd.getAll('NAME'));
          console.log(fd.getAll('STARTDATE'));
          console.log(fd.getAll('ENDDATE'));
          this.$http.post('/system/system/querydata', fd).then(body => {
            this.userList = body.data.data.pageall;
            this.totlepage = body.data.data.totlepage;
            console.log(this.userList);
          })
      }
        },

        export2Excel(a1, a2, a3, a4,a5) {
          if(a4.length!=0){
            this.startTime=a4[0];
            this.endTime=a4[1];
          }
          let fd = new FormData();
          fd.append('NAME', a1);//传姓名
          fd.append('SEX', a2);//传性别
          fd.append('AGE', a3);//传年龄
          fd.append('CARDID', a4);//传手机号
          fd.append('REMARKS', a5);//传备注
          //fd.append('CONFERENCEID', a4);//传会议ID
          //fd.append('ACCOUNT_MODE', a5);//传收款方式
          //fd.append('STARTDATE', this.startTime);//传开始时间
          //fd.append('ENDDATE', this.endTime);//传结束时间
          fd.append('page', this.currentpage);//传当前页
          fd.append('type',this.type);//传类型
          this.$http.post('/system/system/exportuser', fd).then(body => {
            this.excelData = body.data.data;
            console.log(this.excelData);
            require.ensure([], () => {
              const { export_json_to_excel } = require('@/excel/Export2Excel.js');//引入文件
              const tHeader = ['姓名', '性别','年龄', '联系方式','备注'];
              // 上面设置Excel的表格第一行的标题
              const filterVal = [ 'NAME', 'SEX','AGE','CARDID','REMARKS'];
              // 上面的index、phone_Num、school_Name是tableData里对象的属性
              const list = this.excelData;  //把data里的tableData存到list
              // console.log(list);
              const data = this.formatJson(filterVal, list);
              export_json_to_excel(tHeader, data, '用户表单');
            })
          })//传数据
        },

        formatJson(filterVal, jsonData) {
          console.log(jsonData);
          return jsonData.map(v => filterVal.map(j => v[j]));
        },

       created(){
      this.getUser()
    },
        computed: {
        //计算属性：返回页码数组，这里会自动进行脏检查，不用$watch();
        pagenums: function()
        {//分页
          //初始化前后页边界
          let lowPage = 1;
          let highPage = this.totlepage;
          let pageArr = [];
          if(this.totlepage > this.visiblepage)
          {//总页数超过可见页数时，进一步处理；
            let subVisiblePage = Math.ceil(this.visiblepage/2);
            if(this.currentpage > subVisiblePage && this.currentpage < this.totlepage - subVisiblePage +1)
            {//处理正常的分页
              lowPage = this.currentpage - subVisiblePage;
              highPage = this.currentpage + subVisiblePage -1;
            }
            else if(this.currentpage <= subVisiblePage)
            {//处理前几页的逻辑
              lowPage = 1;
              highPage = this.visiblepage;
            }
            else
            {
            //处理后几页的逻辑
              lowPage = this.totlepage - this.visiblepage + 1;
              highPage = this.totlepage;
            }
          }
          //确定了上下page边界后，要准备压入数组中了
          while(lowPage <= highPage)
          {
            pageArr.push(lowPage);
            lowPage ++;
          }
          return pageArr;

      }
   }
}
   </script>

<style scoped>
   .display1{
    padding-left:20px;
    padding-top:10px;
  }
  .display2{
    border:solid 2px  #e0e0e0;
    height: 500px;
    /*text-align: center;*/
    width: 98%;
    padding-left:10px;
    padding-right:10px;
    background-color:  #fff;
  }
  .user{
    float:left;
    margin:10px 0 10px 0;
    font-weight: bold;
    background-color: #00AAFF;
    border:solid 1px #00AAFF;
    border-radius: 5px;
    width: 40%;
    padding:5px;
  }
  .sort{
    color:#1C93FC;
  }
  .sort:hover{
    color:#5CB0FA;
  }
  table{
    border-collapse: collapse;
    width:100%;
    margin-top: 10px;
    margin-left: 20px;
  }
  th{
    font-size: 14px;
    border:solid 1px #ccc;
    font-weight: bold;
    padding:5px;
    background-color: #F1F1F1;
  }
   table,td{
     border:solid 1px #ccc;
     padding:5px;
     text-align: center;
     font-size:14px;
   }
   .btn1{
     font-size: 18px;
     padding: 10px 10px;
     border-top-left-radius: 3px;
     border-top-right-radius: 3px;
     border: 1px solid #ccc;
     cursor: pointer;
     background: #f0f0f0;
     margin-bottom: -1px;
     color:#000;
     /*margin-right: 0px;*/
   }
  .btn1:hover{
    background: #e0e0e0;
  }
  .active{
    background: #e0e0e0;
  }
  .btn2{
    margin-left: 0px;
  }
  .input1{
    width:80px;
  }
  .btn3{
    width:80px;
    padding:7px;
    font-size: 14px;
    border-radius: 3px;
    border:none;
    color:white;
    background-color:#338FFC ;
    float: left;
    margin-left: 15px;
    margin-top:13px;
    /*margin-bottom: 5px;*/
  }
  .btn3:hover{
    background-color:#5FA7FE;
  }
  .btn4{
    width:90px;
    padding:7px;
    font-size: 14px;
    border-radius: 3px;
    border:none;
    color:white;
    background-color:#FA4E28 ;
    float: right;
    /*margin-left: 15px;*/
    margin-top: 13px;
    /*margin-bottom: 5px;*/
  }
  .btn4:hover{
    background-color:#FC6F4F;
  }
  .delete:hover{
    color:#C1C1C2
  }
  .page{
    text-align: center;
  }
  .top{
    background: #e0e0e0;
  }
  .el-date-editor--datetimerange.el-input, .el-date-editor--datetimerange.el-input__inner{
    width:360px !important;
  }
</style>
