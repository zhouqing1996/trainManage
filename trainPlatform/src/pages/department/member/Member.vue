<template>
  <div class="display" >
    <button class="btn1" v-on:click="teacher" v-bind:class="{ active: isActiveOne }">教师列表</button>
    <button class="btn1" v-on:click="student" v-bind:class="{ active: isActiveTwo }">学生列表</button>
    <div class="display1" v-show="t">
      <div class="searchmem">
        <div class="meeting" >
          <el-input v-model="inputteacher" placeholder="请输入教师姓名" size="mini"></el-input>
        </div>
        <button class="btn3" v-on:click="searchTch()">搜索</button>
        <button class="btn3" type="text" @click="addateacher();">添加</button>
<!--        添加教师信息-->
        <el-dialog title="添加教师信息" :visible.sync="dialogFormVisible">
          <el-form :model="form">
            <el-form-item label="姓名" :label-width="formLabelWidth">
              <el-input style="width: 350px;" v-model="form.teacherName" auto-complete="off"></el-input>
            </el-form-item>
            <el-form-item label="工号" :label-width="formLabelWidth">
              <el-input style="width: 350px;" v-model="form.job_num" auto-complete="off"></el-input>
            </el-form-item>
            <el-form-item label="专业" :label-width="formLabelWidth">
              <select v-model="form.major" style="font-size:15px;width:180px;" >
                <option disabled value="">选择</option>
                <option :value="Item.id" v-for="Item in majorList" >{{Item.major}}</option>
              </select>
            </el-form-item>
            <el-form-item label="身份证号" :label-width="formLabelWidth">
              <el-input style="width: 350px;" v-model="form.identify" auto-complete="off"></el-input>
            </el-form-item>
            <el-form-item label="性别" :label-width="formLabelWidth">
              <select v-model="form.sex" style="font-size:15px;width:180px;" >
                <option disabled value="">选择</option>
                <option value="1">男</option>
                <option value="2">女</option>
              </select>
            </el-form-item>
            <el-form-item label="职称" :label-width="formLabelWidth">
              <el-input style="width: 350px;" v-model="form.post" auto-complete="off"></el-input>
            </el-form-item>
            <el-form-item label="职务" :label-width="formLabelWidth">
              <el-input style="width: 350px;" v-model="form.rank" auto-complete="off"></el-input>
            </el-form-item>
            <el-form-item label="联系电话" :label-width="formLabelWidth">
              <el-input style="width: 350px;" v-model="form.phone" auto-complete="off"></el-input>
            </el-form-item>
            <el-form-item label="邮箱" :label-width="formLabelWidth">
              <el-input style="width: 350px;" v-model="form.email" auto-complete="off"></el-input>
            </el-form-item>
          </el-form>
          <div slot="footer" style="align-content: center" class="dialog-footer">
            <el-button type="primary" @click="submit(form,1)">提交</el-button>
            <el-button @click="reset(1)">重置</el-button>
          </div>
        </el-dialog>
<!--        批量导入-->
        <button type="button" class="btn3 " id="import-table" v-on:click="uploadFile()">批量导入</button>
        <input id="imFile" style="display: none" type="file" @change="importfxx(this,1)"
               accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"/>
<!--        批量导出-->
        <button type="button" class="btn3" id="import-table1" v-on:click="exportFile()">批量导出</button>
<!--        <input id="exFile" style="display: none" type="file" @change="exportfxx(this,1)"-->
<!--               accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"/>-->
      </div>
      <table id="teacherStastics">
        <tr>
          <th>序号</th>
          <th>姓名 </th>
          <th>工号</th>
          <th>专业</th>
          <th>身份证</th>
          <th>性别</th>
          <th>职称</th>
          <th>职务</th>
          <th>联系电话</th>
          <th>邮箱</th>
          <th>操作</th>
          <th>删除</th>
        </tr>
        <tr v-for=" (teacher,index) in teacherList">
          <td>{{(currentpage-1)*8+index+1}}</td>
          <td>{{teacher.teacherName}}</td>
          <td>{{teacher.job_num}}</td>
          <td>{{teacher.major}} </td>
          <td>{{teacher.identity}}</td>
          <td v-if='teacher.sex==1'>男</td>
          <td v-if='teacher.sex==2'>女</td>
          <td>{{teacher.rank}}</td>
          <td>{{teacher.post}}</td>
          <td>{{teacher.contactPhone}}</td>
          <td>{{teacher.email}}</td>
          <td>
            <router-link :to="{ name: 'alterteacher', query: { id: teacher.id,flag:2 }}"><i class="el-icon-edit"></i>修改</router-link>
          </td>
          <td>
            <span @click="deleteTch(teacher.id,teacher.job_num)"><i class="el-icon-delete"></i>删除</span>
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

    <div class="display1" v-show="s">
      <div class="searchmem">
        <div class="meeting" >
          <el-input v-model="inputstudent" placeholder="请输入学生姓名" size="mini"></el-input>
        </div>
        <button class="btn3 icon-sousuo" v-on:click="searchStu()">搜索</button>
        <!--<router-link :to="{ name: 'addstudent',params:{ data: 'add'} }">-->
          <!--<button class="btn3">添加</button>-->
        <!--</router-link>-->
        <button class="btn3" type="text" @click=" getClassData();">添加</button>

        <el-dialog title="添加学生信息" :visible.sync="dialogFormVisible1">
          <el-form :model="form">
            <el-form-item label="姓名" :label-width="formLabelWidth">
              <el-input style="width: 350px;" v-model="studentform.stuName" auto-complete="off"></el-input>
            </el-form-item>
            <el-form-item label="学号" :label-width="formLabelWidth">
              <el-input style="width: 350px;" v-model="studentform.sno" auto-complete="off"></el-input>
            </el-form-item>
            <el-form-item label="身份证号" :label-width="formLabelWidth">
              <el-input style="width: 350px;" v-model="studentform.identity" auto-complete="off"></el-input>
            </el-form-item>
            <el-form-item label="性别" :label-width="formLabelWidth">
              <select v-model="studentform.sex" style="font-size:15px;width:180px;" >
                <option disabled value="">选择</option>
                <option value="1">男</option>
                <option value="2">女</option>
              </select>
            </el-form-item>
            <el-form-item label="出生日期" :label-width="formLabelWidth">
              <el-input style="width: 350px;" v-model="studentform.bornDate" auto-complete="off"></el-input>
            </el-form-item>
            <el-form-item label="班级" :label-width="formLabelWidth">
              <select v-model="studentform.class" style="font-size:15px;width:180px;" >
                <option disabled value="">选择</option>
                <option v-bind:value="Item.id" v-for="Item in classList" >{{Item.grade}}-{{Item.major}}专业{{Item.className}}</option>
              </select>
            </el-form-item>
            <el-form-item label="联系电话" :label-width="formLabelWidth">
              <el-input style="width: 350px;" v-model="studentform.contactPhone" auto-complete="off"></el-input>
            </el-form-item>
            <el-form-item label="邮箱" :label-width="formLabelWidth">
              <el-input style="width: 350px;" v-model="studentform.email" auto-complete="off"></el-input>
            </el-form-item>
          </el-form>
          <div slot="footer" style="align-content: center" class="dialog-footer">
            <el-button type="primary" @click=" insertstudent()">提交</el-button>
            <el-button @click="resetstudent()">重置</el-button>
          </div>
        </el-dialog>
        <button type="button" class="btn3" id="import-table" v-on:click="uploadFile1()">批量导入</button>
        <input id="imFile1" style="display: none" type="file" @change="importfxx(this,2)"
               accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"/>
        <button type="button" class="btn3" id="import-table1" v-on:click="exportFile1()">批量导出</button>
        <!--        <input id="exFile" style="display: none" type="file" @change="exportfxx(this,1)"-->
        <!--               accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"/>-->
      </div>
      <table id="studentStastics">
        <tr>
          <th>序号</th>
          <th>姓名</th>
          <th>学号 </th>
          <th>身份证</th>
          <th>性别</th>
          <th>出生日期</th>
          <th>班级</th>
          <th>联系电话</th>
          <th>邮箱</th>
          <!--<th>权限</th>-->
          <th>操作</th>
          <th>删除</th>
        </tr>
        <tr v-for=" (stu,index) in studentList">
          <td>{{(currentpage-1)*8+index+1}}</td>
          <td>{{stu.stuName}}</td>
          <td>{{stu.sno}}</td>
          <td>{{stu.identity}}</td>
          <td v-if='stu.sex==1'>男</td>
          <td v-if='stu.sex==2'>女</td>
          <td v-if='stu.sex==""'>男</td>
          <td>{{stu.bornDate}}</td>
          <td>{{stu.grade}}-{{stu.major}}专业{{stu.className}}</td>
          <td>{{stu.contactPhone}}</td>
          <td>{{stu.email}}</td>
          <!--<td v-if='stu.authority==5'>学生负责人</td>-->
          <!--<td v-if='stu.authority==6'>普通学生</td>-->
          <td>
            <router-link :to="{ name: 'alterstudent', query: { studentId: stu.id }}"><i class="el-icon-edit"></i>修改</router-link>
          </td>
          <td>
            <span @click="deleteStu(stu.id,stu.sno)"><i class="el-icon-delete"></i>删除</span>
          </td>
        </tr>
      </table>
      <div class="page">
        <ul class="pagination pagination-sm"><!--分页-->
          <li class="page-item" v-if="currentpage!=1">
            <span class="page-link" v-on:click="prepage(currentpage)">上一页</span>
          </li>
          <li class="page-item" v-for="index in pagenums" v-bind:class="{ active: currentpage == index} ">
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
  import { common3, common4 } from '../../../main'


  export default {
    name: 'teacher',
    data () {
      return {
        teacherList: [],
        studentList: [],
        majorList: [],
        classList: [],
        inputteacher: '', // 教师姓名
        inputstudent: '', // 学生姓名
        isActiveOne: true,
        isActiveTwo: false,
        s: false,
        t: true,
        // 翻页相关
        currentpage: 1,
        totalpage: '',
        visiblepage: 10,
        dialogFormVisible: false,
          dialogFormVisible1: false,
        form: {
          teacherName: '',
          job_num:'',
          sex:'',
          phone:'',
          email:'',
          post:'',
          rank:'',
          identify:'',
          major:'',
        },
        studentform: {
          stuName:'',
          sno:'',
          identity:'',
          sex:'',
          bornDate:'',
          class:'',
          contactPhone:'',
          email:'',
        },
        formLabelWidth: '120px'
      }
    },
    methods: {
        getMajorData: function () {
            let that = this
            this.$http.get('/department/major/getdata?page=1').then(function (res) {
                that.majorInfo = res.data.data['major']
            })
        },

        teacher: function () {
            this.getTchData()
            this.currentpage = 1
            this.t = true
            this.s = false
            this.isActiveOne = true
            this.isActiveTwo = false
        },

        student: function () {
            this.getStuData()
            this.currentpage = 1
            this.t = false
            this.s = true
            this.isActiveOne = false
            this.isActiveTwo = true
        },
        getMajorData: function () {
            let that = this;
            this.$http.get('/department/member/getmajordata?page=1').then(function (res) {
                console.log(res.data);
                that.majorList = res.data.data[0];
            })
        },
        resetstudent: function () {
            this.studentform.stuName = '';
            this.studentform.sno = '';
            this.studentform.identity = '';
            this.studentform.sex = '';
            this.studentform.bornDate = '';
            this.studentform.class = '';
            this.studentform.contactPhone = '';
            this.studentform.email = '';
        },
        getTchData: function () {
            let that = this
            that.$http.post('/department/member/getteacherdata', {
                page: that.currentpage
            }).then(function (res) {
                that.teacherList = res.data.data[0]
                that.totalpage = res.data.data[1]
            })
        },

        getClassData: function () {
            this.dialogFormVisible1 = true
            this.studentform.stuName = '';
            this.studentform.sno = '';
            this.studentform.identity = '';
            this.studentform.sex = '';
            this.studentform.bornDate = '';
            this.studentform.class = '';
            this.studentform.contactPhone = '';
            this.studentform.email = '';
            let that = this
            this.$http.get('/department/member/getclassdata').then(function (res) {
                console.log(res.data)
                that.classList = res.data.data
            })
        },
        addateacher: function () {
            this.dialogFormVisible = true;
            this.form.teacherName = '';
            this.form.job_num = '';
            this.form.sex = '';
            this.form.phone = '';
            this.form.email = '';
            this.form.post = '';
            this.form.rank = '';
            this.form.identify = '';
            this.form.major = '';
        },

        getStuData: function () {
            let that = this
            this.$http.get('/department/member/getstudentdata?page=1').then(function (res) {
                that.studentList = res.data.data[0]
                that.totalpage = res.data.data[1]
            })
        },
        submit: function (form, flag) {
            if (flag == 1) {
                this.insertteacher(flag)
            } else if (flag == 2) {
                this.updatemajor(this.majorId)
            }
        },
        insertteacher: function (flag) {
            let that = this;
            if (that.form.teacherName != '' && that.form.job_num != '' && that.form.phone != '' && that.form.email != '' && that.form.identify && that.form.major != '') {
                this.$http.post('/department/member/insertteacher', {
                    job_num: that.form.job_num,
                    name: that.form.teacherName,
                    identify: that.form.identify,
                    sex: that.form.sex,
                    rank: that.form.rank,
                    post: that.form.post,
                    phone: that.form.phone,
                    email: that.form.email,
                    major: that.form.major,
                }).then((res) => {
                    console.log(res.data)
                    console.log(flag)
                    if (res.data.data == 1) {
                        if (flag == 1) {
                            that.dialogFormVisible = false;
                            alert("教师信息添加成功！")
                            common3.$emit('showResList', "教师信息添加成功！");
                        } else {
                            common3.$emit('showResList', "教师信息修改成功！");
                        }
                        // that.$router.push({ path:"/department/member/member?name=教师信息"});
                    } else {
                        console.log(res.data.message);
                        alert("教师信息添加失败！");
                    }
                });
            } else {
                alert("教师信息不能为空！");
            }
        },
        insertstudent: function () {
            let that = this;
            if (that.addsno != '' && that.studentName != '') {
                this.$http.post('/department/member/insertstudent', {
                    studentName: that.studentform.stuName,
                    studentSno: that.studentform.sno,
                    studentIdentify: that.studentform.identity,
                    studentSex: that.studentform.sex,
                    studentBorndate: that.studentform.bornDate,
                    studentClass: that.studentform.class,
                    studentPhone: that.studentform.contactPhone,
                    studentEmail: that.studentform.email,
                }).then((res) => {
                    if (res.data.message == "添加成功") {
                        alert("学生信息添加成功！");
                        this.dialogFormVisible1 = false;
                        common4.$emit('showResList', 1);
                        that.$router.push({path: "/department/member/member?name=学生信息"});
                    } else {
                        alert("学生信息添加失败！");
                    }
                });
            } else {
                alert("学生信息不能为空！");
            }
        },
        searchTch: function () {
            let that = this
            that.$http.get('/department/member/queryteacher', {
                params: {
                    teacherName: that.inputteacher,
                    page: that.currentpage
                }
            }).then(res => {
                that.teacherList = res.data.data[0]
                that.totalpage = res.data.data[1]
            }).catch(function (error) {
                console.log(error)
            })
        },

        searchStu: function () {
            let that = this
            that.$http.get('/department/member/querystudent', {
                params: {
                    studentName: that.inputstudent,
                    page: that.currentpage
                }
            }).then(res => {
                this.studentList = res.data.data[0]
                that.totalpage = res.data.data[1]
            }).catch(function (error) {
                console.log(error)
            })
        },

        deleteTch: function (id, job_num) {
            this.$confirm('此操作将永久删除该专业, 是否继续?', '提示', {
                confirmButtonText: '确定',
                cancelButtonText: '取消',
                type: 'warning'
            }).then(() => {
                let that = this
                that.$http.post('/department/member/deleteteacher', {id: id, job_num: job_num}).then(function (res) {
                    if (res.data.message == 1) {
                        that.getTchData()
                        alert('删除教师成功！')
                    }
                })
            }).catch(() => {
            });
        },

        deleteStu: function (studentId, sno) {
            let that = this
            that.$http.post('/department/member/deletestudent', {
                studentId: studentId,
                sno: sno
            }).then(function (res) {
                if (res.data.message == 1) {
                    that.getStuData()
                    alert('删除学生成功！')
                }
            })
        },

        pageChange: function (page) { // 分页
            if (this.currentpage != page) {
                this.currentpage = page
            }
            if (this.t == true) {
                this.searchTch()
            } else {
                this.searchStu()
            }
        },

        prepage: function (page) { // 上一页
            page--
            if (this.currentpage != page) {
                this.currentpage = page
            }
            if (this.t == true) {
                this.searchTch()
            } else {
                this.searchStu()
            }
        },

        nextpage: function (page) { // 下一页
            page++
            if (this.currentpage != page) {
                this.currentpage = page
            }
            if (this.t == true) {
                this.searchTch()
            } else {
                this.searchStu()
            }
        },

        importfxx(obj, flag) {
            let _this = this
            let inputDOM = this.$refs.inputer   // 通过DOM取文件数据
            this.file = event.currentTarget.files[0]
            var rABS = false // 是否将文件读取为二进制字符串
            var f = this.file
            var reader = new FileReader()
            // if (!FileReader.prototype.readAsBinaryString) {
            FileReader.prototype.readAsBinaryString = function (f) {
                var binary = ''
                var rABS = false // 是否将文件读取为二进制字符串
                var pt = this
                var wb // 读取完成的数据
                var outdata
                var reader = new FileReader()
                reader.onload = function (e) {
                    var bytes = new Uint8Array(reader.result)
                    var length = bytes.byteLength
                    for (var i = 0; i < length; i++) {
                        binary += String.fromCharCode(bytes[i])
                    }
                    var XLSX = require('xlsx')
                    if (rABS) {
                        wb = XLSX.read(btoa(fixdata(binary)), { // 手动转化
                            type: 'base64'
                        })
                    } else {
                        wb = XLSX.read(binary, {
                            type: 'binary'
                        })
                    }
                    // outdata就是你想要的东西 excel导入的数据
                    outdata = XLSX.utils.sheet_to_json(wb.Sheets[wb.SheetNames[0]])
                    // excel 数据再处理
                    let arr = []
                    console.log(flag)
                    if (flag == 1) {
                        outdata.map(v => {
                            let obj = {}
                            obj.job_num = v.工号
                            obj.major = v.专业名称
                            obj.teacherName = v.教师姓名
                            obj.identity = v.身份证号
                            obj.sex = v.性别
                            obj.rank = v.职称
                            obj.post = v.职务
                            obj.contactPhone = v.联系电话
                            obj.email = v.邮箱
                            arr.push(obj)
                        })
                    } else {
                        outdata.map(v => {
                            let obj = {}
                            obj.sno = v.学号
                            obj.major = v.专业
                            obj.stuName = v.姓名
                            obj.grade = v.年级
                            obj.class = v.班级
                            obj.sex = v.性别
                            obj.bornDate = v.出生日期
                            obj.identity = v.身份证号
                            obj.contactPhone = v.联系电话
                            obj.email = v.邮箱
                            arr.push(obj)
                        })
                    }

                    _this.memberList = [...arr]
                    let data = {
                        data: JSON.stringify(_this.memberList)
                    }
                    if (flag == 1) {
                        _this.$http.post('/department/member/importexcel', data).then(body => {
                            console.log(body)
                            alert("导入成功")
                            _this.getTchData()
                        })
                    } else if (flag == 2) {
                        _this.$http.post('/department/member/importexcel2', data).then(body => {
                            alert(body.data.message)
                            _this.getStuData()
                        })
                    }
                }
                reader.readAsArrayBuffer(f)
            }
            if (rABS) {
                reader.readAsArrayBuffer(f)
            } else {
                reader.readAsBinaryString(f)
            }
        },
        // exportfxx(obj,flag){
        //
        // },
        uploadFile: function () { // 点击导入按钮
            this.imFile.click()
        },
        uploadFile1: function () { // 点击导入按钮
            this.imFile1.click()
        },
        // 数据格式化
        formatJson(filterVal, jsonData) {
            return jsonData.map(v => filterVal.map(j => v[j]))
        },
        exportFile: function () {
            //点击导出教师列表
            // require.ensure([], () => {
            //     let { export_json_to_excel } = require('@/excel/Export2Excel.js');
            //     // 表头
            //     let tHeader = ['序号','教师姓名', '工号', '专业', '证件号码','性别','职称','行政职位','电话号码','邮件'];
            //     //表头对应字段名，要和下面数据key对应
            //     let filterVal = ['id','teacherName', 'job_num', 'major', 'identity','sex','rank','post','contactPhone','email'];
            //     // 数据来源
            //     let list = this.teacherList;
            //     let data = this.formatJson(filterVal,list);
            //     var name = "教师信息表";
            //     export_json_to_excel(tHeader,data,name);
            require.ensure([], () => {
                const {export_json_to_excel} = require('@/excel/Export2Excel.js');//引入文件
                const tHeader = ['序号', '教师姓名', '工号', '专业', '证件号码', '性别', '职称', '行政职位', '电话号码', '邮件'];
                ;
                // 上面设置Excel的表格第一行的标题
                const filterVal = ['id', 'teacherName', 'job_num', 'major', 'identity', 'sex', 'rank', 'post', 'contactPhone', 'email'];
                // 上面的index、phone_Num、school_Name是tableData里对象的属性
                const list = this.teacherList;  //把data里的tableData存到list
                // console.log(list);
                const data = this.formatJson(filterVal, list);
                export_json_to_excel(tHeader, data, "教师信息表");
                // let list1 =  [
                //     {iAutoID: "4737", Address: "海门市海门镇南海路南、长江路东侧", AuctionDate: "0000-00-00", Area: ""},
                //     {iAutoID: "21337", Address: "上海市浦东新区东至:A13B-01地块,南至:A13B-01地块,西至:国展路,北至:A13B-01地块",Area: ""},
                //     {iAutoID: "17373", Address: "白马大道以东、建业路以北", AuctionDate: "0000-00-00", Area: ""},
                //     {iAutoID: "17271", Address: "黄陂区横店街川龙大道以东、横中路以北", AuctionDate: "0000-00-00", Area: "黄陂"},
                //     {iAutoID: "20577", Address: "南通市海门四甲镇东渐大道北侧、军工路南侧", AuctionDate: "0000-00-00", Area: ""},
                //     {iAutoID: "18929", Address: "奥诺斯特以东、黄河路以南", AuctionDate: "2018-09-21", Area: ""}]
                // let data1 = this.formatJson(filterVal, list); //数据格式化
                // var index1 = '资源列表';//导出时文件名
                // exportJsonToExcel(tHeader, data, index1); //导出文件
            })
        },
        // },
        exportFile1: function () {
            require.ensure([], () => {
                const {export_json_to_excel} = require('@/excel/Export2Excel.js');//引入文件
                const tHeader = ['序号', '姓名', '学号', '身份证', '性别', '出生日期', '班级', '联系电话', '邮箱'];
                ;
                // 上面设置Excel的表格第一行的标题
                const filterVal = ['id', 'stuName', 'sno', 'identity', 'sex', 'bornData', 'class', 'contactPhone', 'email'];
                // 上面的index、phone_Num、school_Name是tableData里对象的属性
                const list = this.teacherList;  //把data里的tableData存到list
                // console.log(list);
                const data = this.formatJson(filterVal, list);
                export_json_to_excel(tHeader, data, "学生信息表");
            });
        }
    },
      created() {
          this.user = this.$store.state.mutations.user
          this.name = this.$route.query.name
          this.getTchData()
          this.getMajorData()
          if (this.name == '学生信息') {
              this.student();
              // this.currentpage = 1
              // this.t = false
              // this.s = true
              // this.isActiveOne = false
              // this.isActiveTwo = true
          }
      },
        mounted() {
            this.imFile = document.getElementById('imFile')
            this.imFile1 = document.getElementById('imFile1')
            this.exFile = document.getElementById('exFile')
            common3.$on('showResList', (data) => {
                this.getTchData()
            })
            common4.$on('showResList', (data) => {
                this.getStuData()
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
    border: 1px solid #E5E7E9;
    cursor: pointer;
    background: #fff;
    margin-bottom: -1px;
    color: #000;
    width: 120px;
    /*margin-right: 0px;*/
  }
  .active {
    color: #01A6FE;
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
  .display{
    padding-left: 5px;
    padding-top: 10px;
  }

  .display1{
    border: solid 1px #E5E7E9;
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
</style>
