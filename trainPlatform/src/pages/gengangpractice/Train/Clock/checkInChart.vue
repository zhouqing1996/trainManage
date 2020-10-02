<template>
  <div  v-cloak id="myChart" ref="myChart"></div>
</template>

<script>
  import echarts from 'echarts'
  export default {
    name: 'checkInChart',
    props: ['xdata', 'ydata'],
    data () {
      return {
        data1: this.xdata,
        data2: this.ydata
      }
    },
    mounted () {
      console.log(this.xdata)
      console.log(this.ydata)
      this.draw()
      // setTimeout(function () {
      //   this.draw()
      // }, (Number(3)))
    },
    watch: {
      'xdata': function (newVal) {
        this.draw()
      }
    },
    methods: {
      draw () {
        console.log('hanshu')
        // 实例化echarts对象
        var myChart = echarts.init(this.$refs.myChart)
        var xdata = this.xdata
        var ydata = this.ydata
        // 绘制条形图
        myChart.setOption({
          color: ['#3398DB'],
          tooltip: {
            trigger: 'axis',
            axisPointer: { // 坐标轴指示器，坐标轴触发有效
              type: 'shadow' // 默认为直线，可选为：'line' | 'shadow'
            }
          },
          grid: {
            left: '3%',
            right: '4%',
            bottom: '3%',
            containLabel: true,
            borderWidth: 1
          },
          xAxis: [
            {
              type: 'category',
              data: xdata,
              axisTick: {
                alignWithLabel: true
              }
            }
          ],
          yAxis: [
            {
              type: 'value'
            }
          ],
          series: [
            {
              name: '已签到',
              type: 'bar',
              barWidth: '60%',
              data: ydata
            }
          ]
        })
        // 重绘画布
        setTimeout(function () {
          myChart.resize()
        }, 2000)
        // myChart.resize()
        /* 窗口自适应，关键代码 */
        setTimeout(function () {
          window.onresize = function () {
            myChart.resize()
          }
        }, 200)
      }
    }
  }
</script>

<style scoped>
  #myChart {
    width: 95%;
    height: 350px;
    margin: 20px auto;
    border: 1px solid #CCC
  }
  [v-cloak] {
    display: none;
  }
</style>
