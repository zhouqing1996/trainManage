<div class="container" >
  <div class="list-wrapper">
    <ul>
      <li>标题</li>
      <li>操作</li>
      <li><p @click="$router.push({path:'/requirement/templateadd',query:{id: 0}})">+ 新建模板</p></li>
    </ul>
    <ul v-for="item in surveyList">
      <li v-text="item.title"></li>
      <li>
        <router-link tag="span" :to="{ path: address , query: { id: item.id,flag:10}}">
          预览模板
        </router-link>
        <span v-if="!item.state" @click="iterator = editItem(item); iterator.next()">编辑模板</span>
        <span  v-if="!item.state" @click="iterator = showPrompt1(item)">修改类别</span>
        <span @click="iterator = deleteItem(item); iterator.next()">删除</span>
      </li>
      <li></li>
    </ul>
  </div>
  <div class="overlay" v-show="isShowPrompt">
    <div class="prompt-box">
      <header>
        <span>提示</span>
        <a href="javascript:;" @click="isShowPrompt = false">&times;</a>
      </header>
      <p>{{ promptText }}</p>
      <footer>
        <span @click="iterator.next(); isShowPrompt = false">确定</span>
        <span @click="isShowPrompt = false">取消</span>
      </footer>
    </div>
  </div>
</div>
