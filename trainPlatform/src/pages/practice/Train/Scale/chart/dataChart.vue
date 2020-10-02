<template>
  <div id="myChart" ref="myChart" ></div>
</template>

<script>
  import echarts from 'echarts/lib/echarts'
  import 'echarts/lib/chart/pie'
  import 'echarts/lib/component/tooltip'
  import 'echarts/lib/component/toolbox'
    export default {
      name: 'dataChart',
      props:['opt','rate'],
      data () {
        return {
          ans: [],
        }
      },
      mounted () {
        this.draw()
      },
      methods: {
        draw () {
          for (var i = 0; i<this.opt.length;i++){
            this.ans[i]={};
            this.ans[i].value = this.rate[i];
            this.ans[i].name = this.opt[i];
          }
          const myChart = echarts.init(this.$refs.myChart)
          myChart.setOption({
            tooltip: {
              trigger: 'item',
              formatter: "{a} <br/>{b} : {c} ({d}%)"
            },
            series: [
              {
                name: '选择人数',
                type: 'pie',
                radius: '60%',
                center: ['50%', '50%'],
                data: this.ans,
                itemStyle: {
                  emphasis: {
                    shadowBlur: 10,
                    shadowOffsetX: 0,
                    shadowColor: 'rgba(0, 0, 0, 0.5)'
                  }
                }
              }]
          })
        }
      }
    }
</script>

<style scoped>
  #myChart{
    width: 300px;
    height: 300px;
  }
</style>
