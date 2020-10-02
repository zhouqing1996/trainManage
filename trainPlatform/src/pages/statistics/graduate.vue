<template>
  <div class="display1">
    <button class="btn1">实习总结</button>
    <div class="display2">
      <div>
        <span>学号</span>
        <el-input style="width: 20%" v-model="studentId" placeholder="请输入学号"></el-input>
        <el-button type="primary" icon="el-icon-search" v-on:click="search(studentId)">查找</el-button>
      </div>
      <div class="inforStyle">
        <!--基本信息-->
        <span class="tltleStyle"><i class="el-icon-document"></i>基本信息</span>
        <div class="distence">
          <span class="span">姓名:</span><span>{{infor.name}}</span>
          <span class="span">学号:</span><span>{{infor.studentId}}</span>
          <span class="span">学校:</span><span>{{infor.school}}</span>
          <span class="span">专业:</span><span>{{infor.major}}</span>
          <span class="span">班级:</span><span>{{infor.class}}</span><br>
          <span class="span">实习地点：</span><span>{{infor.place}}</span>
          <span class="span">实习时间:</span><span>{{infor.startTime}}-{{infor.endTime}}</span>
          <span class="span">指导教师：</span><span>{{infor.teacher}}</span>
        </div>
        <!--实习总结-->
        <span class="tltleStyle"><i class="el-icon-date"></i>实习总结</span>
        <div class="distence">
          <div v-for="(item,index) in infor.summary">
              <span class="span" style="color: #D68910;">第{{index+1}}次总结</span><br>
              <span class="span">标题:</span><span>{{item.title}}</span><br>
              <span class="span">内容:</span>
              <!--<span>{{item.content}}</span>-->
              <span>
                <el-input
                  style="width: 60%"
                  type="textarea"
                  :rows="4"
                  :disabled="true"
                  placeholder="请输入内容"
                  v-model="item.content">
                </el-input>
              </span>
          </div>
        </div>
        <!--教师评价-->
        <span class="tltleStyle"><i class="el-icon-star-off"></i>教师评价</span>
        <div class="distence">
          <span class="span">内容:</span>
              <span>
                <el-input
                  style="width: 60%"
                  type="textarea"
                  :rows="4"
                  :disabled="true"
                  placeholder="请输入内容"
                  v-model="infor.teacherEvaluation">
                </el-input>
              </span>
        </div>
        <!--学校评价-->
        <span class="tltleStyle"><i class="el-icon-edit-outline"></i>学校评价</span>
        <div class="distence">
          <span class="span">内容:</span>
           <span>
                <el-input
                  style="width: 60%"
                  type="textarea"
                  :rows="4"
                  :disabled="true"
                  placeholder="请输入内容"
                  v-model="infor.schoolEvaluation">
                </el-input>
              </span>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
    export default {
        name: 'graduate',
      data () {
          return {
            studentId: '',
            infor: {
              name: '张三',
              studentId: '123321',
              school: '华中师范大学',
              class: '1班',
              major: '学前教育',
              place: '华师附属幼儿园',
              startTime: '2019/1/1',
              endTime: '2020/1/1',
              teacher: '李四',
              summary: [
                {
                title: '第一次总结',
                content: '第一次总结第一次总结第一次总结第一次总结第一次总结第一次总结第一次总结第一次总结第一次总结第一次总结第一次总结第一次总结第一次总结第一次总结第一次总结第一次总结第一次总结第一次总结第一次总结第一次总结第一次总结第一次总结第一次总结第一次总结第一次总结第一次总结第一次总结第一次总结第一次总结第一次总结第一次总结第一次总结第一次总结第一次总结第一次总结第一次总结第一次总结第一次总结第一次总结第一次总结第一次总结第一次总结第一次总结第一次总结第一次总结第一次总结第一次总结'
                },
                {
                  title: '第二次总结',
                  content: '第二次总结'
                },
                {
                  title: '第三次总结',
                  content: '第三次总结'
                }, {
                  title: '第四次总结',
                  content: '第四次总结'
                }
              ],
              teacherEvaluation: '教师评价',
              schoolEvaluation: '学校评价'
            }
          }
      },
      methods: {
          search (id) {
            console.log(id)
          }
      }
    }
</script>

<style scoped>
  .display1 {
    padding-left: 10px;
    padding-top: 10px;
  }

  .display2 {
    border: solid 1px #e0e0e0;
    height: auto;
    /*text-align: center;*/
    width: 98%;
    padding-left: 10px;
    padding-right: 10px;
    padding-top:20px;
    background-color: #fff;
  }

  .btn1 {
    font-size: 16px;
    padding: 10px 10px;
    border-top-left-radius: 3px;
    border-top-right-radius: 3px;
    border: 1px solid #ccc;
    cursor: pointer;
    background: #fff;
    margin-bottom: -1px;
    color: #000;
    /*margin-right: 0px;*/
  }
  .inforStyle{
    margin-top: 20px;
  }
  .distence{
    /*padding-left: 20px;*/
    padding-top: 10px;
    padding-bottom: 20px;
  }
  .span{
    padding-left: 20px;
    color: #3498DB;
    font-weight: bold;
    padding-top: 10px;
  }
  .tltleStyle{
    color: #2E86C1;font-weight: bold;font-size: 18px;
  }
</style>
