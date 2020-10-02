<template>
  <div class="display1">
    <button class="btn1">签到统计</button>
      <div class="display2">
        <CheckIn></CheckIn>
        <CheckInChart></CheckInChart>
      </div>
  </div>
</template>

<script>
    import CheckInChart from './chart/checkInChart'
    import CheckIn from './checkIn'
    export default {
        name: 'Index',
      components: {CheckIn, CheckInChart}

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
</style>
