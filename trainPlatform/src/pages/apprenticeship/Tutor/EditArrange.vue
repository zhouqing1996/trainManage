<template>
  <div>
    <div class="block">
      <el-form ref="form" label-position="left" inline>
        <el-form-item label="发布企业" class="a">
          <span>{{ myrow.comName }}</span>
        </el-form-item>
        <el-form-item label="发布时间" class="a">
          <span>{{ myrow.time }}</span>
        </el-form-item>
        <el-form-item label="招聘专业" class="a">
          <span>{{ myrow.grade }}级{{ myrow.major }}</span>
        </el-form-item>
        <el-form-item label="安排学生" class="a">
          <div v-for="(item, index) in alldata" :key="index" style="margin-bottom: 20px">
            <span>{{item.tutor.tutorName}}</span>
            <span style="margin-left: 20px" v-if="item.arranst.length > 0">
              已安排：
              <span v-for="item2 in item.arranst" :key="item2.identity">
                {{item2.stuName}}
              </span>
            </span>
            <el-select v-model="mydata[index]" multiple placeholder="请选择" style="margin-left: 20px">
              <el-option v-for="item3 in item.allst" :key="item3.value" :label="item3.label" :value="item3.value">
              </el-option>
            </el-select>
          </div>
        </el-form-item>
      </el-form>
      <el-button @click="cancelArran">取消</el-button>
      <el-button type="primary" @click="ensureArran">确定</el-button>
    </div>
  </div>
</template>

<script>
    export default {
      name: 'EditArrange',
      props: ['myrow'],
      data () {
          return {
            power: this.$store.getters.showPower,
            formLabelWidth: '150px',
            alldata: [],
            mydata: []
          }
      },
      methods: {
        getData () {
          let that = this
          this.$http.post('/apprenticeship/tutor/getst', {id: this.myrow.id, grade: this.myrow.grade, majorId: this.myrow.majorId}).then(function (res) {
            that.alldata = res.data.data
            console.log(res.data.data)
          })
        },
        ensureArran () {
          console.log(this.mydata)
          var arr = this.deepClone(this.mydata)
          var arr2 = []
          var arr3 = []
          for (var i in arr) {
            arr2.push(arr[i])
          }
          for (var j = 0; j < arr2.length; j++) {
            for (var k in arr2[j]) {
              arr3.push(arr2[j][k])
            }
          }
          var arrsort = arr3.sort()
          var flag = 0
          for (var l = 0; l < arrsort.length - 1; l++) {
            if (arrsort[l] == arrsort[l + 1]) {
              flag = 1
              alert('一个学生不能同时选择多个导师！')
              break
            }
          }
          if (flag == 0) {
            var tutor = []
            for (var m = 0; m < this.alldata.length; m++) {
              tutor.push(this.alldata[m].tutor.identity)
            }
            let that = this
            this.$http.post('/apprenticeship/tutor/arrangest', {st: this.mydata, tutor: tutor}).then(function (res) {
              alert('安排成功！')
              that.$emit('child', 1)
            })
          }
        },
        cancelArran () {
          this.$emit('child', 0)
        },
        deepClone (data) {
          var type = typeof data
          var obj
          if (type === 'array') {
            obj = []
          } else if (type === 'object') {
            obj = {}
          } else {
            // 不再具有下一层次
            return data
          }
          if (type === 'array') {
            for (var i = 0, len = data.length; i < len; i++) {
              obj.push(this.deepClone(data[i]))
            }
          } else if (type === 'object') {
            for (var key in data) {
              obj[key] = this.deepClone(data[key])
            }
          }
          return obj
        }
      },
      mounted () {
        this.getData()
      }
    }
</script>

<style scoped>
  .a {
    margin-right: 0;
    margin-bottom: 0;
    width: 100%;
  }
</style>
