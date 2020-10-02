<template>
      <div id="myChart" ref="myChart"></div>
</template>

<script>
  import echarts from 'echarts'
    export default {
      name: 'checkInChart',
      // 引入完整的echarts
      // created () {
      //   // 调用绘制图表的方法
      //   this.draw()
      // },
      data () {
        return {
           text: ['点', '击', '柱', '子', '或', '者', '两', '指', '在', '触', '屏', '上', '滑', '动', '能', '够', '自', '动', '缩', '放'],
           getData: [220, 182, 191, 234, 290, 330, 310, 123, 442, 321, 90, 149, 210, 122, 133, 334, 198, 123, 125, 220]
        }
      },
      mounted () {
        // 调用绘制图表的方法
        this.draw()
      },
      methods: {
        draw () {
          // 实例化echarts对象
          var myChart = echarts.init(this.$refs.myChart)

          var dataAxis = this.text
          var data = this.getData
          var yMax = 500
          var dataShadow = []

          for (var i = 0; i < data.length; i++) {
            dataShadow.push(yMax)
          }

          // 绘制条形图
          myChart.setOption({
            title: {
              text: '签到情况',
              subtext: 'checkIn'
            },
            xAxis: {
              data: dataAxis,
              axisLabel: {
                inside: true,
                textStyle: {
                  color: '#fff'
                }
              },
              axisTick: {
                show: false
              },
              axisLine: {
                show: false
              },
              z: 10
            },
            yAxis: {
              axisLine: {
                show: false
              },
              axisTick: {
                show: false
              },
              axisLabel: {
                textStyle: {
                  color: '#999'
                }
              }
            },
            dataZoom: [
              {
                type: 'inside'
              }
            ],
            series: [
              { // For shadow
                type: 'bar',
                itemStyle: {
                  normal: {color: 'rgba(0,0,0,0.05)'}
                },
                barGap: '-100%',
                barCategoryGap: '40%',
                data: dataShadow,
                animation: false
              },
              {
                type: 'bar',
                itemStyle: {
                  normal: {
                    color: new echarts.graphic.LinearGradient(
                      0, 0, 0, 1,
                      [
                        {offset: 0, color: '#83bff6'},
                        {offset: 0.5, color: '#188df0'},
                        {offset: 1, color: '#188df0'}
                      ]
                    )
                  },
                  emphasis: {
                    color: new echarts.graphic.LinearGradient(
                      0, 0, 0, 1,
                      [
                        {offset: 0, color: '#2378f7'},
                        {offset: 0.7, color: '#2378f7'},
                        {offset: 1, color: '#83bff6'}
                      ]
                    )
                  }
                },
                data: data
              }
            ]
          })

          // Enable data zoom when user click bar.
          var zoomSize = 6
          myChart.on('click', function (params) {
            console.log(dataAxis[Math.max(params.dataIndex - zoomSize / 2, 0)])
            myChart.dispatchAction({
              type: 'dataZoom',
              startValue: dataAxis[Math.max(params.dataIndex - zoomSize / 2, 0)],
              endValue: dataAxis[Math.min(params.dataIndex + zoomSize / 2, data.length - 1)]
            })
          })
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
</style>
