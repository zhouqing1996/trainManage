<template>
    <div >
      <div>
        <!--日期-->
        <div class="block">
          <span class="demonstration">时间段选择</span>
          <el-date-picker
            v-model="time"
            type="daterange"
            align="right"
            unlink-panels
            range-separator="至"
            start-placeholder="开始日期"
            end-placeholder="结束日期"
            :picker-options="pickerOptions2">
          </el-date-picker>
        </div>
        <!--专业-->
        <div>
          专业
          <el-select v-model="major" placeholder="专业选择" style="margin-top: 10px;">
            <el-option-group
              v-for="group in majorOptions"
              :key="group.label"
              :label="group.label">
              <el-option
                v-for="item in group.options"
                :key="item.value"
                :label="item.label"
                :value="item.value">
              </el-option>
            </el-option-group>
          </el-select>
        </div>
        <!--班级-->
        <div>
          班级
          <el-select v-model="className" placeholder="班级选择" style="margin-top: 10px;">
            <el-option-group
              v-for="group in classOptions"
              :key="group.label"
              :label="group.label">
              <el-option
                v-for="item in group.options"
                :key="item.value"
                :label="item.label"
                :value="item.value">
              </el-option>
            </el-option-group>
          </el-select>
        </div>
        <!--学生学号-->
        <div>
          学号
          <el-input style="width: 18%;margin-top: 10px;"
                    placeholder="请输入学号"
                    v-model="studentId"
                    clearable>
          </el-input>
          <el-button type="primary" icon="el-icon-search" v-on:click="search(time,major,className,studentId)">查找</el-button>
          <el-button type="primary" icon="el-icon-delete" v-on:click="reset">清空</el-button>
        </div>
      </div>

    </div>
</template>

<script>
    export default {
        name: 'checkIn',
      methods: {
          // 查找
        search (time, major, className, studentId) {
            console.log(time, major, className, studentId)
        },
        // 清空
        reset () {
          this.time = ''
          this.major = ''
          this.className = ''
          this.studentId = ''
        }
      },
      data () {
        return {
          pickerOptions2: {
            shortcuts: [{
              text: '最近一周',
              onClick (picker) {
                const end = new Date()
                const start = new Date()
                start.setTime(start.getTime() - 3600 * 1000 * 24 * 7)
                picker.$emit('pick', [start, end])
              }
            }, {
              text: '最近一个月',
              onClick (picker) {
                const end = new Date()
                const start = new Date()
                start.setTime(start.getTime() - 3600 * 1000 * 24 * 30)
                picker.$emit('pick', [start, end])
              }
            }, {
              text: '最近三个月',
              onClick (picker) {
                const end = new Date()
                const start = new Date()
                start.setTime(start.getTime() - 3600 * 1000 * 24 * 90)
                picker.$emit('pick', [start, end])
              }
            }]
          },
          time: '',
          majorOptions: [{
            label: '学前教育学院',
            options: [{
              value: 'Shanghai',
              label: '学前教育1'
            }, {
              value: 'Beijing',
              label: '学前教育2'
            }]
          }, {
            label: '教育信息技术学院',
            options: [{
              value: 'Chengdu',
              label: '教育技术学1'
            }, {
              value: 'Shenzhen',
              label: '教育技术学2'
            }]
          }],
          major: '',
          classOptions: [{
            label: '学前教育1',
            options: [{
              value: 'Shanghai',
              label: '学前教育1班'
            }, {
              value: 'Beijing',
              label: '学前教育2班'
            }]
          }, {
            label: '教育技术学',
            options: [{
              value: 'Chengdu',
              label: '教育技术学1班'
            }, {
              value: 'Shenzhen',
              label: '教育技术学2班'
            }]
          }],
          className: '',
          studentId: ''
        }
      }
    }
</script>

<style scoped>

</style>
