<template>
  <div class="display1">
<!--    <button class="btn1 active">企业信息列表</button>-->
    <button class="btn1 " v-on:click="major1" v-bind:class="{active: isActiveOne}">企业信息列表</button>
    <button class="btn1 " v-on:click="class1" v-bind:class="{active: isActiveTwo}">导师信息列表</button>
    <div class="display2" v-show="major">
      <div class="searchmem">
        <div class="meeting" >
          <el-input v-model="inputname" placeholder="请输入企业名称" size="mini"></el-input>
        </div>
        <button class="btn3 el-icon-search" v-on:click="searchCom()">搜索</button>
        <el-button class="btn3 el-icon-circle-plus-outline" @click="dialogVisible = true">添加</el-button>
        <Addcom1 v-if="dialogVisible" v-on:child="listenTo" :dialogVisible="dialogVisible" :major="major"></Addcom1>
      </div>
      <table id="companyStastics">
        <tr>
          <th>企业名称 </th>
          <th>企业统一社会信用代码</th>
          <th>企业地址</th>
          <th>企业电话</th>
          <th>企业邮箱</th>
          <th>企业简介</th>
          <th>操作</th>
          <th>删除</th>
        </tr>
        <tr v-for=" (member,key) in companyList" :key="key">
          <td>{{member.comName}}</td>
          <td>{{member.comAccount}}</td>
          <td>{{member.comAddress}}</td>
          <td>{{member.comPhone}}</td>
          <td>{{member.comEmail}}</td>
          <td>
            <a class="info" style="cursor: pointer" @click="introDetail(member.id)"><i class="el-icon-view"></i>查看详情</a>
          </td>
          <td>
            <router-link :to="{ name: 'altercom', query: { companyId: member.id }}"><i class="el-icon-edit"></i>修改</router-link>
          </td>
          <td>
            <span @click="deleteCom(member.id)"><i class="el-icon-delete"></i>删除</span>
          </td>
        </tr>
      </table>
      <el-dialog :visible.sync="showDetail">
        <el-form :model="detailList">
          <el-form-item label="企业名称" :label-width="formLabelWidth">
            <el-input style="width: 350px;" v-model="detailList.comName" auto-complete="off"></el-input>
          </el-form-item>
          <el-form-item label="企业简介" :label-width="formLabelWidth">
            <el-input style="width: 350px;" v-model="detailList.introdution" auto-complete="off"></el-input>
          </el-form-item>
        </el-form>
        <div slot="footer" style="align-content: center" class="dialog-footer">
          <el-button @click="closeDialog1">关闭</el-button>
        </div>
      </el-dialog>
      <div class="page">
        <ul class="pagination pagination-sm"><!--分页-->
          <li class="page-item" v-if="currentpage!=1">
            <span class="page-link" v-on:click="prepage(currentpage)">上一页</span>
          </li>
          <li class="page-item" v-for="(index,key2) in pagenums" :key="key2" v-bind:class="{ active: currentpage == index} ">
            <span class="page-link" v-on:click="pageChange(index)">{{ index }}</span>
          </li>
          <li class="page-item" v-if="currentpage!=totalpage">
            <span class="page-link" v-on:click="nextpage(currentpage)">下一页</span>
          </li>
          <li class="page-item">
            <span class="page-link">共<i>{{totalpage}}</i>页</span>
          </li>
        </ul>
      </div>
    </div>
    <div class="display2" v-show="classes">
      <div class="searchmem">
        <div class="meeting" >
          <el-input v-model="inputnametutor" placeholder="请输入导师姓名" size="mini"></el-input>
        </div>
        <button class="btn3 el-icon-search" v-on:click="searchTutorroot()">搜索</button>
        <el-button class="btn3 el-icon-circle-plus-outline" @click="dialogVisible2 = true">添加</el-button>
        <Addtutor v-if="dialogVisible2" v-on:child2="listenTotutor" :dialogVisible2="dialogVisible2" :companyList="companyList" ></Addtutor>
        <button class="btn3 el-icon-circle-plus-outline" @click="exportFile()">导出</button>
<!--        <button class="btn3 el-icon-circle-plus-outline" type="text" @click="dialogFormVisible = true;classform.tutorName='';classform.identity='';classform.companyAccount='';-->
<!--        classform.sex='';classform.craft='';classform.phone='';classform.profile='';">添加</button>-->
<!--        <el-dialog title="添加导师信息" :visible.sync="dialogFormVisible">-->
<!--          <el-form :model="classform">-->
<!--            <el-form-item label="姓名" :label-width="formLabelWidth">-->
<!--              <el-input style="width: 350px;" v-model="classform.tutorName" auto-complete="off"></el-input>-->
<!--            </el-form-item>-->
<!--            <el-form-item label="身份证号" :label-width="formLabelWidth">-->
<!--              <el-input style="width: 350px;" v-model="classform.identity" auto-complete="off"></el-input>-->
<!--            </el-form-item>-->
<!--            <el-form-item label="公司" :label-width="formLabelWidth">-->
<!--              <select v-model="classform.companyAccount" style="font-size:15px;width:180px;" >-->
<!--                <option disabled value="">选择</option>-->
<!--                <option :value="Item.companyAccount" v-for="Item in companyList" >{{Item.comName}}</option>-->
<!--              </select>-->
<!--            </el-form-item>-->
<!--            <el-form-item label="性别" :label-width="formLabelWidth">-->
<!--              <select v-model="classform.sex" style="font-size:15px;width:180px;" >-->
<!--                <option disabled value="男">选择</option>-->
<!--                <option value="1">男</option>-->
<!--                <option value="2">女</option>-->
<!--              </select>-->
<!--            </el-form-item>-->
<!--            <el-form-item label="岗位" :label-width="formLabelWidth">-->
<!--              <el-input style="width: 350px;" v-model="classform.craft" auto-complete="off"></el-input>-->
<!--            </el-form-item>-->
<!--            <el-form-item label="电话" :label-width="formLabelWidth">-->
<!--              <el-input style="width: 350px;" v-model="classform.phone" auto-complete="off"></el-input>-->
<!--            </el-form-item>-->
<!--            <el-form-item label="简介" :label-width="formLabelWidth">-->
<!--              <el-input style="width: 350px;" v-model="classform.profile" auto-complete="off"></el-input>-->
<!--            </el-form-item>-->
<!--          </el-form>-->
<!--          <div slot="footer" class="dialog-footer">-->
<!--            <el-button @click="visible(0)">取 消</el-button>-->
<!--            <el-button type="primary" @click="visible(1)">确 定</el-button>-->
<!--          </div>-->
<!--          <div slot="footer" style="align-content: center" class="dialog-footer">-->
<!--            <el-button type="primary" @click="submit(classform)">提交</el-button>-->
<!--            <el-button @click="reset()">重置</el-button>-->
<!--          </div>-->
<!--        </el-dialog>-->
        <el-dialog title="修改导师信息" :visible.sync="dialogFormVisible2">
          <el-form :model="classform">
            <el-form-item label="姓名" :label-width="formLabelWidth">
              <el-input style="width: 350px;" v-model="classform.tutorName" auto-complete="off"></el-input>
            </el-form-item>
<!--            导师的身份证号不能修改-->
            <el-form-item label="身份证号" :label-width="formLabelWidth">
<!--              <span style="width: 350px"; v-model="classform.identity" auto-complete="off"></span>-->
              <el-input style="width: 350px;" v-model="classform.identity"  :disabled="true" auto-complete="off"></el-input>
            </el-form-item>
            <el-form-item label="公司" :label-width="formLabelWidth">
              <select v-model="classform.companyAccount" style="font-size:15px;width:350px;height:25px" >
<!--                <option  selected value="">{{classform.companyAccount}}</option>-->
                <option selected value="classform.companyAccount">选择</option>
                <option v-for="item in companyList" :value=item.comAccount>{{item.comAccount}}</option>
              </select>
            </el-form-item>
            <el-form-item label="性别" :label-width="formLabelWidth">
              <select v-model="classform.sex" style="font-size:15px;width:180px;" >
                <option  value="classform.sex">选择</option>
                <option value="1">男</option>
                <option value="2">女</option>
              </select>
            </el-form-item>
            <el-form-item label="岗位" :label-width="formLabelWidth">
              <el-input style="width: 350px;" v-model="classform.craft" auto-complete="off"></el-input>
            </el-form-item>
            <el-form-item label="电话" :label-width="formLabelWidth">
              <el-input style="width: 350px;" v-model="classform.phone" auto-complete="off"></el-input>
            </el-form-item>
            <el-form-item label="简介" :label-width="formLabelWidth">
              <el-input type="textarea" style="width: 350px;row-span: 8;" placeholder="请输入内容" v-model="classform.profile"></el-input>
            </el-form-item>
          </el-form>
          <div slot="footer" style="align-content: center" class="dialog-footer">
            <el-button type="primary" @click="submit(classform)">提交</el-button>
<!--            <el-button @click="resettutorroot()">重置</el-button>-->
            <el-button @click="closeDialog">取消</el-button>
          </div>
        </el-dialog>
      </div>
      <table id="TutorStastics">
      <tr>
        <th>序号</th>
        <th>企业账户 </th>
        <th>身份证号</th>
        <th>姓名</th>
        <th>性别</th>
        <th>岗位</th>
        <th>电话</th>
        <th>简介</th>
        <th>操作</th>
        <th>删除</th>
      </tr>
      <tr v-for=" (member,key) in classInfo" >
        <td>{{key+1}}</td>
        <td>{{member.companyAccount}}</td>
        <td>{{member.identity}}</td>
        <td>{{member.tutorName}}</td>
        <td>{{member.sex}}</td>
        <td>{{member.craft}}</td>
        <td>{{member.phone}}</td>
        <td>{{member.profile}}</td>
        <td>
          <a @click="getedittutorroot(member.identity)">修改</a>
        </td>
        <td>
          <span @click="deletetutorroot(member.identity)"><i class="el-icon-delete"></i>删除</span>
        </td>
      </tr>
    </table>
      <div class="page">
      <ul class="pagination pagination-sm"><!--分页-->
        <li class="page-item" v-if="currentpage!=1">
          <span class="page-link" v-on:click="prepage(currentpage)">上一页</span>
        </li>
        <li class="page-item" v-for="(index,key2) in pagenums" :key="key2" v-bind:class="{ active: currentpage == index} ">
          <span class="page-link" v-on:click="pageChange(index)">{{ index }}</span>
        </li>
        <li class="page-item" v-if="currentpage!=totalpage">
          <span class="page-link" v-on:click="nextpage(currentpage)">下一页</span>
        </li>
        <li class="page-item">
          <span class="page-link">共<i>{{totalpage}}</i>页</span>
        </li>
      </ul>
    </div>
    </div>
  </div>
</template>

<script>

  import Addcom1 from './Addcom1'
  import { common } from '../../main'
  import Addtutor from './Addtutor'

  export default {
    name: 'company',
    components: {
        Addtutor,
        Addcom1
    },
    data () {
      return {
        companyList: [],
          detailList: {
              comName: '',
              introdution: ''
          },
          classInfo: [],
          classes: false,
          major: true,
          isEdit: false,
          isActiveOne: true,
          isActiveTwo: false,
          dialogFormVisible: false,
          dialogFormVisible1: false,
          dialogFormVisible2: false,
          classform: {
            // 导师信息
              companyAccount: '',
              tutorName: '',
              identity: '',
              craft: '',
              sex: '',
              phone: '',
              profile: ''
          },
          formLabelWidth: '120px',
          inputnametutor: '', // 导师姓名
          inputname: '', // 企业名称
        // 翻页相关
         currentpage: 1,
         totalpage: '',
         visiblepage: 10,
         dialogVisible: false,
         dialogVisible2: false,
         showDetail: false
      }
    },
    methods: {
        major1: function () {
            this.major = true
            this.classes = false
            this.isActiveOne = true
            this.isActiveTwo = false
            this.getComData()
        },
        class1 () {
            let that = this
            this.$http.post('/company/tutor/getdataroot').then(function (res) {
                console.log(res.data)
                that.classInfo = res.data.data
            })
            this.major = false
            this.classes = true
            this.isActiveOne = false
            this.isActiveTwo = true
        },
        introDetail: function (companyId) {
            let that = this
            that.$http.post('/company/company/getdetail', {companyId: companyId}).then(function (res) {
                console.log(res.data.data)
                that.detailList.comName = res.data.data.comName
                that.detailList.introdution = res.data.data.introdution
            })
            that.showDetail = true
        },
        searchTutorroot: function () {
            this.$http.post('/company/tutor/querytutorroot', {
                tutorName: this.inputnametutor, page: this.currentpage
            }).then(res => {
                console.log(res.data)
                this.classInfo = res.data.data[0]
                this.totalpage = res.data.data[1]
            }).catch(function (error) {
                console.log(error)
            })
        },
        listenTotutor: function (visible) {
            if (visible == 1) {
                this.dialogVisible2 = false
                this.class1()
                // common.$emit('change', '信息已修改！')
            } else {
                this.dialogVisible2 = false
            }
        },
        updatetutorroot: function (identity) {
            console.log(identity)
            let that = this
            if (that.classform.identity != '') {
                this.$http.post('/company/tutor/altertutorroot', {
                    identity: identity,
                    tutorName: that.classform.tutorName,
                    companyAccount: that.classform.companyAccount,
                    craft: that.classform.craft,
                    sex: that.classform.sex,
                    phone: that.classform.phone,
                    profile: that.classform.profile
                }).then(function (res) {
                    if (res.data.data == 1) {
                        alert('导师信息修改成功！')
                    } else {
                        alert('导师信息修改失败！')
                    }
                    that.class1()
                    that.dialogFormVisible2 = false
                    common.$emit('showResList', '导师信息修改！')
                }).catch(function (error) {
                    console.log(error)
                })
            } else {
                alert('导师身份证号不能为空！')
            }
        },
        submit: function (form) {
            console.log(form)
            this.updatetutorroot(this.identity)
        },
        getedittutorroot: function (identity) {
            this.identity = identity
            console.log(identity)
            let that = this
            this.$http.post('/company/tutor/getedittutorroot', {identity: identity}).then(function (res) {
                console.log(res.data)
                that.classform.identity = res.data.data.identity
                that.classform.profile = res.data.data.profile
                that.classform.sex = res.data.data.sex
                that.classform.phone = res.data.data.phone
                that.classform.craft = res.data.data.craft
                that.classform.tutorName = res.data.data.tutorName
                that.classform.companyAccount = res.data.data.companyAccount
                that.dialogFormVisible2 = true
            })
        },
        closeDialog () {
            this.dialogFormVisible2 = false
        },
        closeDialog1 () {
            this.showDetail = false
        },
        deletetutorroot: function (tutorId) {
            console.log(tutorId)
            let that = this
            this.$http.post('/company/tutor/deletetutorroot', {tutorId: tutorId}).then(function (res) {
                console.log((res.data))
                if (res.data.message == 1) {
                    // that.getTutorData();
                    that.class1()
                    alert('删除导师成功！')
                }
            })
        },
        // 数据格式化
        formatJson (filterVal, jsonData) {
            return jsonData.map(v => filterVal.map(j => v[j]))
        },
        exportFile: function () {
            require.ensure([], () => {
                const {export_json_to_excel} = require('@/excel/Export2Excel.js')// 引入文件
                const tHeader = ['企业账户', '身份证号', '姓名', '性别', '岗位', '电话', '简介']
                // 上面设置Excel的表格第一行的标题
                const filterVal = ['companyAccount', 'identity', 'tutorName', 'sex', 'craft', 'phone', 'profile']
                // 上面的index、phone_Num、school_Name是tableData里对象的属性
                const list = this.classInfo // 把data里的tableData存到list
                // console.log(list);
                const data = this.formatJson(filterVal, list)
                export_json_to_excel(tHeader, data, '导师信息表')
            })
        },
        getComData: function () {
            let that = this
            this.$http.get('/company/company/getdata?page=1').then(function (res) {
                console.log(res.data)
                that.companyList = res.data.data[0]
                that.totalpage = res.data.data[1]
            })
        },
        searchCom: function () {
            this.$http.post('/company/company/querycompany', {
                companyName: this.inputname, page: this.currentpage
            }).then(res => {
                console.log(res.data)
                this.companyList = res.data.data[0]
                this.totlepage = res.data.data[1]
            }).catch(function (error) {
                console.log(error)
            })
        },

        deleteCom: function (companyId) {
            console.log(companyId)
            let that = this
            this.$http.post('/company/company/deletecompany', {companyId: companyId}).then(function (res) {
                console.log(res.data)
                if (res.data.message == 1) {
                    that.getComData()
                    alert('删除企业成功！')
                }
            })
        },

        listenTo (visible) {
            if (visible == 1) {
                this.dialogVisible = false
                this.getComData()
                // common.$emit('change', '信息已修改！')
            } else {
                this.dialogVisible = false
            }
        },

        pageChange: function (page) { // 分页
            if (this.currentpage != page) {
                this.currentpage = page
            }
            this.getComData(this.currentpage)
        },

        prepage: function (page) { // 上一页
            page--
            if (this.currentpage != page) {
                this.currentpage = page
            }
            this.getComData(this.currentpage)
        },

        nextpage: function (page) { // 下一页
            page++
            if (this.currentpage != page) {
                this.currentpage = page
            }
            this.getComData(this.currentpage)
        }
    },
    created () {
      this.getComData()
      common.$on('showResList', (data) => {
        this.getComData()
      })
    },
    computed: {
      // 计算属性：返回页码数组，这里会自动进行脏检查，不用$watch();
      pagenums: function () { // 分页
        // 初始化前后页边界
        let lowPage = 1
        let highPage = this.totalpage
        let pageArr = []
        if (this.totalpage > this.visiblepage) { // 总页数超过可见页数时，进一步处理；
          let subVisiblePage = Math.ceil(this.visiblepage / 2)
          if (this.currentpage > subVisiblePage && this.currentpage < this.totalpage - subVisiblePage + 1) { // 处理正常的分页
            lowPage = this.currentpage - subVisiblePage
            highPage = this.currentpage + subVisiblePage - 1
          } else if (this.currentpage <= subVisiblePage) { // 处理前几页的逻辑
            lowPage = 1
            highPage = this.visiblepage
          } else { // 处理后几页的逻辑
            lowPage = this.totalpage - this.visiblepage + 1
            highPage = this.totalpage
          }
        }
        // 确定了上下page边界后，要准备压入数组中了
        while (lowPage <= highPage) {
          pageArr.push(lowPage)
          lowPage++
        }
        return pageArr
      }
    }

  }
</script>

<style scoped>

  .btn1 {
    font-size: 16px;
    padding: 10px 10px;
    border-top-left-radius: 3px;
    border-top-right-radius: 3px;
    border: 1px solid #ccc;
    cursor: pointer;
    background-color: #fff;
    margin-bottom: -1px;
    color: #000;
    width: 150px;
    /*margin-right: 0px;*/
  }

  .btn1:hover {
    background: #e0e0e0;
  }

  /*.active {*/
    /*color: #000;*/
  /*}*/
  .btn3 {
    width: 80px;
    padding: 7px;
    font-size: 14px;
    border-radius: 3px;
    border: none;
    color: white;
    background-color: #338FFC;
    float: left;
    margin-left: 5px;
    margin-top: 17px;
    margin-bottom: 5px;
  }

  .btn3:hover {
    background-color: #5FA7FE;
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
    text-align: center;
  }

  table, td {
    border: solid 1px #ccc;
    padding: 5px;
    text-align: center;
    font-size: 14px;
  }
  .display1 {
    padding-left: 5px;
    padding-top: 10px;
  }

  .display2 {
    border: solid 1px #e0e0e0;
    height: 600px;
    /*text-align: center;*/
    width: 98%;
    padding-left: 5px;
    padding-right: 5px;
    background-color: #fff;
  }
  .page {
    text-align: center;
  }
  .meeting{
    float:left;
    margin:14px 0 10px 0;
    font-weight: bold;
    background-color: #00AAFF;
    border:solid 1px #00AAFF;
    border-radius: 5px;
    width: 20%;
    padding:2px;
  }
  .info{
    font-size: 12px;
  }
</style>
