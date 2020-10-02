<template>
  <el-carousel :interval="400000000" type="card" height="500px">
    <el-carousel-item v-for="(item,i) in userList" :key="i">
      <router-link :to="{ path:item.path , query: { id:item.id }}" tag="h3" class="font">{{ item.name }}</router-link>
    </el-carousel-item>
  </el-carousel>
</template>

<script>
    export default {
      data () {
        return {
          userList: []
        }
      },
      // 方法中可以设置数据处理和数据的获取等等
      methods: {
        getUser () {
          this.$http.get('/api/list')// 代替http://localhost:3000/list
            .then((res) => {
              console.log(res.data)
              this.userList = res.data
              console.log(this.userList)
            }, (err) => {
              console.log(err)
            })
        }
      },
      // 生命周期钩子中可以进行初始数据的获取
      created () {
        this.getUser()
      }
    }
</script>

<style scoped>
  .el-carousel__item h3 {
    color: #475669;
    font-size: 45px;
    opacity: 0.75;
    line-height: 500px;
    margin: 0;
  }

  .el-carousel__item:nth-child(2n) {
    background-color: #99a9bf;
  }

  .el-carousel__item:nth-child(2n+1) {
    background-color: #d3dce6;
  }
  .font{
    font-size: 30px;
    text-align: center;
  }
</style>
