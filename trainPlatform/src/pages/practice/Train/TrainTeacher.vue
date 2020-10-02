<template>
  <div>
    <div>
      <el-button type="primary" style="margin-bottom: 10px" @click="setTime()">设置提醒</el-button>
    </div>
    <div v-if="view == 1">
      <div v-show="!tabvisible">
        <el-tabs v-model="activeNow" @tab-click="handleClick">
          <el-tab-pane label="进行中" name="first"><TechOngoing :ondata="ondata" v-on:ongoingchild="listenOn"></TechOngoing></el-tab-pane>
          <el-tab-pane label="已结束" name="second"><TechCompelete :compdata="compdata" v-on:compeletechild="listenComp"></TechCompelete></el-tab-pane>
        </el-tabs>
      </div>
      <div v-if="tabvisible">
        <TrainTab :row="row"></TrainTab>
      </div>
    </div>
    <div v-if="view == 2">
      <span><el-button @click="back2()">返回</el-button></span>
      <span><el-button type="primary" @click="addremind()">添加</el-button></span>
      <el-table key='mytable' :data="tableData" style="width: 100%;margin-top: 10px">
        <el-table-column label="重复" width="180">
          <template slot-scope="scope">
            <span style="margin-left: 2px" v-for="item in scope.row.repeat" :key="item">
              <span v-if="item == 1">每周一</span>
              <span v-if="item == 2">每周二</span>
              <span v-if="item == 3">每周三</span>
              <span v-if="item == 4">每周四</span>
              <span v-if="item == 5">每周五</span>
              <span v-if="item == 6">每周六</span>
              <span v-if="item == 7">每周七</span>
            </span>
          </template>
        </el-table-column>
        <el-table-column label="内容" width="180">
          <template slot-scope="scope">
            <span>{{scope.row.content}}</span>
          </template>
        </el-table-column>
        <el-table-column label="实习" width="360">
          <template slot-scope="scope">
            <span style="margin-left: 2px" v-for="(item, index) in scope.row.recruit" :key="index">
              {{item}}
            </span>
          </template>
        </el-table-column>
        <el-table-column label="操作">
          <template slot-scope="scope">
            <el-button type="primary" size="mini" @click="handleEdit(scope.$index, scope.row)">编辑</el-button>
            <el-button type="danger" size="mini" @click="handleDelete(scope.row.id)">删除</el-button>
          </template>
        </el-table-column>
      </el-table>
      <el-dialog title="添加提醒" :visible.sync="dialogVisible">
        <el-form :model="form">
          <el-form-item label="内容" :label-width="formLabelWidth">
            <el-input v-model="form.content" autocomplete="off"></el-input>
          </el-form-item>
          <el-form-item label="重复" :label-width="formLabelWidth">
            <el-checkbox :indeterminate="isIndeterminate" v-model="checkAll" @change="handleCheckAllChange">全选</el-checkbox>
            <div style="margin: 15px 0;"></div>
            <el-checkbox-group v-model="form.checkedDays" @change="handleCheckedDaysChange">
              <el-checkbox v-for="city in cities" :label="city" :key="city">{{city}}</el-checkbox>
            </el-checkbox-group>
          </el-form-item>
          <el-form-item label="推送" :label-width="formLabelWidth">
            <el-select v-model="form.push" multiple placeholder="请选择">
              <el-option v-for="item in pushOptions" :key="item.label" :label="item.value" :value="item.label">
              </el-option>
            </el-select>
          </el-form-item>
        </el-form>
        <div slot="footer" class="dialog-footer">
          <el-button @click="dialogVisible = false">取 消</el-button>
          <el-button type="primary" @click="ensure">确 定</el-button>
        </div>
      </el-dialog>
      <el-dialog title="编辑提醒" :visible.sync="dialogVisible2">
        <el-form :model="form2">
          <el-form-item label="内容" :label-width="formLabelWidth">
            <el-input v-model="form2.content" autocomplete="off"></el-input>
          </el-form-item>
          <el-form-item label="重复" :label-width="formLabelWidth">
            <el-checkbox :indeterminate="isIndeterminate" v-model="checkAll" @change="handleCheckAllChange">全选</el-checkbox>
            <div style="margin: 15px 0;"></div>
            <el-checkbox-group v-model="form2.checkedDays" @change="handleCheckedDaysChange">
              <el-checkbox v-for="city in cities" :label="city" :key="city">{{city}}</el-checkbox>
            </el-checkbox-group>
          </el-form-item>
          <el-form-item label="推送" :label-width="formLabelWidth">
            <el-select v-model="form2.push" multiple placeholder="请选择">
              <el-option v-for="item in pushOptions2" :key="item.label" :label="item.value" :value="item.label">
              </el-option>
            </el-select>
          </el-form-item>
        </el-form>
        <div slot="footer" class="dialog-footer">
          <el-button @click="dialogVisible2 = false">取 消</el-button>
          <el-button type="primary" @click="ensure2(form2.id)">确 定</el-button>
        </div>
      </el-dialog>
    </div>
  </div>
</template>

<script>
  import TechOngoing from './TechOngoing'
  import TechCompelete from './TechCompelete'
  import TrainTab from './TrainTab'
  import { common2 } from '../../../main'
  const cityOptions = ['周一', '周二', '周三', '周四', '周五', '周六', '周日']
    export default {
      name: 'TrainTeacher',
      components: {
        TechOngoing,
        TechCompelete,
        TrainTab
      },
      data () {
        return {
          activeNow: 'first',
          power: this.$store.getters.showPower,
          ondata: [],
          compdata: [],
          row: [],
          tabvisible: false,
          dialogVisible2: false,
          view:1,
          tableData:[],
          pushOptions: [],
          checkAll: false,
          pushOptions2: [],
          // stuId:'',
          form: {
            checkedDays: [],
            content: '',
            push: []
          },
          form2: {
            id: '',
            checkedDays: [],
            content: '',
            push: []
          },
          dialogVisible: false,
          formLabelWidth: '50px',
          // checkAll: false,
          cities: cityOptions,
          isIndeterminate: true,
        }
      },
      methods: {
        handleClick () {},
        getDate () {
          let date = new Date()
          let year = date.getFullYear()
          let mon = date.getMonth() < 9 ? '0' + (date.getMonth() + 1) : date.getMonth() + 1
          let day = date.getDate() < 10 ? '0' + date.getDate() : date.getDate()
          return year + '-' + mon + '-' + day
        },
        getData () {
          let that = this
          this.$http.post('/practice/train/traindata', {power: this.power}).then(function (res) {
            console.log(res.data.data)
            let t = that.getDate().split('-')
            let t0 = new Date(t[0], t[1], t[2])
            let t1 = t0.getTime()
            let data = res.data.data
            let arr1 = []
            let arr2 = []
            for (let i = 0; i < data.length; i++) {
              let T = data[i].recruitEnd.split('-')
              let T0 = new Date(T[0], T[1], T[2])
              let T1 = T0.getTime()
              if (T1 >= t1) {
                // 正在实习
                arr1.push(data[i])
              } else {
                // 实习结束
                arr2.push(data[i])
              }
            }
            that.ondata = arr1
            that.compdata = arr2
            console.log(arr1, arr2)
          })
          this.$http.post('/practice/remind/getremind').then(function (res) {
            that.tableData = res.data.data
          })
        },
        listenOn (param, row) {
          if (param == 1) {
            this.row = row
            this.tabvisible = true
          }
        },
        listenComp (param, row) {
          if (param == 1) {
            this.row = row
            this.tabvisible = true
          }
        },
        setTime () {
          this.view = 2
        },
        back2 () {
          this.view = 1
        },
        handleEdit (index, row) {
          let that = this
          this.$http.post('/practice/remind/getpush').then(function (res) {
            that.pushOptions2 = res.data.data
          })
          this.dialogVisible2 = true
          this.form2.content = row.content
          this.form2.id = row.id
          this.form2.push = []
          var a = []
          for (var i = 0; i < row.repeat.length; i++) {
            if (row.repeat[i] == 1) {
              a.push('周一')
            } else if (row.repeat[i] == 2) {
              a.push('周二')
            } else if (row.repeat[i] == 3) {
              a.push('周三')
            } else if (row.repeat[i] == 4) {
              a.push('周四')
            } else if (row.repeat[i] == 5) {
              a.push('周五')
            } else if (row.repeat[i] == 6) {
              a.push('周六')
            } else if (row.repeat[i] == 7) {
              a.push('周日')
            }
          }
          this.form2.checkedDays = a
        },
        handleDelete (id) {
          let that = this
          this.$http.post('/practice/remind/deletremind', { id: id }).then(function (res) {
            that.tableData = res.data.data
            console.log(res.data.data)
          })
        },
        addremind () {
          this.dialogVisible = true
          let that = this
          this.$http.post('/practice/remind/getpush').then(function (res) {
            that.pushOptions = res.data.data
            console.log(res.data.data)
          })
        },
        handleCheckAllChange (val) {
          this.form.checkedDays = val ? cityOptions : []
          this.isIndeterminate = false
        },
        handleCheckedDaysChange (value) {
          let checkedCount = value.length
          this.checkAll = checkedCount === this.cities.length
          this.isIndeterminate = checkedCount > 0 && checkedCount < this.cities.length
        },
        ensure () {
          this.dialogVisible = false
          let that = this
          this.$http.post('/practice/remind/addremind', { form: this.form }).then(function (res) {
            that.tableData = res.data.data
            console.log(res.data.data)
          })
        },
        ensure2 () {
          this.dialogVisible2 = false
          let that = this
          this.$http.post('/practice/remind/editremind', { form: this.form2 }).then(function (res) {
            that.tableData = res.data.data
            console.log(res.data.data)
          })
        },
      },
      mounted () {
        this.getData()
        common2.$on('change', (data) => {
          this.getData()
        })
      }
    }
</script>

<style scoped>

</style>
