<template>
    <div>
      <el-tabs v-model="activeName" @tab-click="handleClick">
        <el-tab-pane label="用户管理" name="first">用户管理</el-tab-pane>
        <el-tab-pane label="配置管理" name="second">配置管理</el-tab-pane>
        <el-tab-pane label="角色管理" name="third">角色管理</el-tab-pane>
      </el-tabs>
    </div>
</template>

<script>
    export default {
      name: 'Scale',
      data () {
        return {
          activeName: 'first'
        }
      }
    }
</script>

<style scoped>

</style>
