<template>
  <div class="display1">
    <button class="btn1">需求分析</button>
    <div class="display2">
      <div id="myChart" ref="myChart"></div>
    </div>
  </div>
</template>

<script>
  import echarts from 'echarts'
export default {
  name: 'demand',
  data () {
    return {
      xAxisData: ['评价指标1', '评价指标2', '评价指标3', '评价指标4', '评价指标5', '评价指标6', '评价指标7', '评价指标8', '评价指标9', '评价指标10'],
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
      var data1 = []
      var data2 = []
      var data3 = []
      var data4 = []

      for (var i = 0; i < 10; i++) {
        // xAxisData.push('Class' + i)
        data1.push((Math.random() * 2).toFixed(2))
        data2.push(Math.random().toFixed(2))
        data3.push((Math.random() * 5).toFixed(2))
        data4.push((Math.random() + 0.3).toFixed(2))
      }

      var itemStyle = {
        normal: {
        },
        emphasis: {
          barBorderWidth: 1,
          shadowBlur: 10,
          shadowOffsetX: 0,
          shadowOffsetY: 0,
          shadowColor: 'rgba(0,0,0,0.5)'
        }
      }
      myChart.setOption({
        // title: {
        //   text: '评价情况',
        //   subtext: 'checkIn'
        // },
        backgroundColor: '#eee',
        legend: {
          data: ['非常好', '很好', '一般', '很差'],
          align: 'left',
          left: 10
        },
        brush: {
          toolbox: ['rect', 'polygon', 'lineX', 'lineY', 'keep', 'clear'],
          xAxisIndex: 0
        },
        toolbox: {
          feature: {
            magicType: {
              type: ['stack', 'tiled']
            },
            dataView: {}
          }
        },
        tooltip: {},
        xAxis: {
          data: this.xAxisData,
          name: 'X Axis',
          silent: false,
          axisLine: {onZero: true},
          splitLine: {show: false},
          splitArea: {show: false}
        },
        yAxis: {
          inverse: true,
          splitArea: {show: false}
        },
        grid: {
          left: 100
        },
        visualMap: {
          type: 'continuous',
          dimension: 1,
          text: ['High', 'Low'],
          inverse: true,
          itemHeight: 200,
          calculable: true,
          min: 0,
          max: 20,
          top: 60,
          left: 10,
          inRange: {
            colorLightness: [0.4, 0.8]
          },
          outOfRange: {
            color: '#bbb'
          },
          controller: {
            inRange: {
              color: '#2f4554'
            }
          }
        },
        series: [
          {
            name: '非常好',
            type: 'bar',
            stack: 'one',
            itemStyle: itemStyle,
            data: data1
          },
          {
            name: '很好',
            type: 'bar',
            stack: 'one',
            itemStyle: itemStyle,
            data: data2
          },
          {
            name: '一般',
            type: 'bar',
            stack: 'two',
            itemStyle: itemStyle,
            data: data3
          },
          {
            name: '很差',
            type: 'bar',
            stack: 'two',
            itemStyle: itemStyle,
            data: data4
          }
        ]
      })
      myChart.on('brushSelected', renderBrushed)

      function renderBrushed (params) {
        var brushed = []
        var brushComponent = params.batch[0]

        for (var sIdx = 0; sIdx < brushComponent.selected.length; sIdx++) {
          var rawIndices = brushComponent.selected[sIdx].dataIndex
          brushed.push('[Series ' + sIdx + '] ' + rawIndices.join(', '))
        }

        myChart.setOption({
          title: {
            backgroundColor: '#333',
            text: 'SELECTED DATA INDICES: \n' + brushed.join('\n'),
            bottom: 0,
            right: 0,
            width: 100,
            textStyle: {
              fontSize: 12,
              color: '#fff'
            }
          }
        })
      }
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
  #myChart {
    width: 95%;
    height: 500px;
    margin: 20px auto;
    border: 1px solid #CCC
  }
</style>
