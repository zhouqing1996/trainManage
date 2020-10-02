<template>
  <div>
    <div v-show="!tabvisible">
      <el-tabs v-model="activeNow" @tab-click="handleClick">
        <el-tab-pane label="进行中" name="first"><Ongoing v-on:ongoingchild="listenOn" :ondata="ondata"></Ongoing></el-tab-pane>
        <el-tab-pane label="已结束" name="second"><Compelete v-on:compeletechild="listenComp" :compdata="compdata"></Compelete></el-tab-pane>
      </el-tabs>
    </div>
    <div v-if="tabvisible">
      <TrainTab :row="row"></TrainTab>
    </div>
  </div>
</template>

<script>
  import Ongoing from './Ongoing'
  import Compelete from './Compelete'
  import TrainTab from './TrainTab'
  import { common2} from '../../../main'
    export default {
      name: 'train',
      components: {
        Ongoing,
        Compelete,
        TrainTab
      },
      data () {
        return {
          activeNow: 'first',
          power: this.$store.getters.showPower,
          ondata: [],
          compdata: [],
          tabvisible: false,
          row: []
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
        }
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
