<template>
  <div class="container">
    <div class="qu-wrap">
      <header>
        <span @click="iterator = backBtn(); iterator.next()"><i class="iconfanhui2"></i>  返回</span>
        <p v-show="!titleEditing" @click="titleEditing = true">{{ tempTitle }}</p>
        <input type="text"
               v-focus
               v-model="title"
               :class="{inlineShow: titleEditing}"
               @focus="_title = title"
               @blur="titleEditing = false"
               @keyup.esc="cancelTitleEdit()"
               @keyup.enter="titleEditing = false">
      </header>
      <div class="qu-content">
        <section class="qu-item"
                 v-for="(item, index) in questions" :key="index"
                 :class="{nowEditing: curIndex === index && topicEditing, optEditing: curIndex === index}">
          <h3 @click="curIndex = index; curOptIndex=''; topicEditing = true">
            <span class="qu-num">{{`Q${index + 1}`}}</span>
            <span class="qu-topic">{{ item.topic }}</span>
            <input type="text"
                   v-focus
                   v-model="topic"
                   @focus="_topic = topic"
                   @blur="curIndex=''; topic=''"
                   @keyup.esc="cancelTopicEdit()"
                   @keyup.enter="doneTopicEdit(index)">
            <span v-if="item.isMandatory" style="color: #E74C3C"> *</span>
          </h3>
          <template v-if="item.type === 'input'">
            <input style="margin-left: 40px" type="text">
            <label id="require-check">
              <input type="checkbox"
                     v-model:checked="item.isMandatory">
              此题是否必填
            </label>
          </template>
          <template v-if="item.type === 'rate'">
            <el-rate v-model="value"></el-rate>
            <label id="require-check">
              <input type="checkbox"
                     v-model:checked="item.isMandatory">
              此题是否必填
            </label>
          </template>
          <template v-if="item.type === 'textarea'">
            <textarea rows="8" cols="80"></textarea>
            <label id="require-check">
              <input type="checkbox"
                     v-model:checked="item.isMandatory">
              此题是否必填
            </label>
          </template>
          <ul v-if="item.type === 'radio'||item.type === 'checkbox'" class="options-list" >
            <li v-for="(option, optIndex) in item.options"
                :class="{optionEditing: curOptIndex === optIndex}" :key="optIndex">
							<span class="optionText"
                    @click="curIndex=index; curOptIndex=optIndex; topicEditing = false">{{ option }}</span>
              <input type="text"
                     v-focus
                     v-model="optionText"
                     @focus="_optionText = optionText"
                     @blur="curIndex=''; optionText=''"
                     @keyup.esc="cancelOptionEdit()"
                     @keyup.enter="doneOptionEdit(index, optIndex)">

              <ul class="opt-ctrl">
                <li v-if="optIndex !== 0"
                    @click="moveUp(optIndex, item.options)"><i class="el-icon-arrow-up"></i>上移</li>
                <li v-if="optIndex !== item.options.length - 1"
                    @click="moveDown(optIndex, item.options)"><i class="el-icon-arrow-down"></i>下移</li>
                <li v-else @click="addOption(item.options)"><i class="el-icon-plus"></i>添加</li>
                <li @click="delOption(optIndex, item.options)"><i class="el-icon-delete"></i>删除</li>
              </ul>
            </li>
            <label id="require-check">
              <input type="checkbox"
                     v-model:checked="item.isMandatory">
              此题是否必填
            </label>
          </ul>
          <ul class="operat-list">
            <li v-if="index !== 0"
                @click="moveUp(index,questions)"><el-button size="mini"><i class="el-icon-arrow-up"></i>上移</el-button></li>
            <li v-if="index !== questions.length - 1"
                @click="moveDown(index,questions)"><el-button size="mini"><i class="el-icon-arrow-down"></i>下移</el-button></li>
            <li @click="reuse(index)"><el-button size="mini"><i class="el-icon-rank"></i>复用</el-button></li>
            <li @click="delQuestion(index)"><el-button size="mini"><i class="el-icon-delete"></i>删除</el-button></li>
          </ul>
          <div></div>
        </section>
        <div class="add-box">
          <p class="qu-type" :class="{expand: isAdding}">
            <span @click="addType('radio')">单选题</span>
            <span @click="addType('checkbox')">多选题</span>
            <span @click="addType('input')">填空题</span>
            <span @click="addType('rate')">打分题</span>
            <span @click="addType('textarea')">文本题</span>
          </p>

          <p class="add-btn" @click="isAdding = !isAdding;">
            <span>+ 添加问题</span>
          </p>
        </div>
      </div>
      <footer>
        <div v-if="flag == 21" class="ctrl-part">
          <span @click="iterator = saveBtn(); iterator.next()" style="background-color: #df5000;color: #fff;height: auto">保存模板</span>
        </div>
        <div v-else class="ctrl-part">
          <select v-model="templateType" style="font-size:14px;">
            <option disabled value="">选择</option>
            <option value="1" >企业相关调查</option>
            <option value="2">学生相关调查</option>
            <option value="3" >教师相关调查</option>
            <option value="4">企业对学校的调查</option>
            <option value="5">毕业生追踪调查</option>
            <option value="6">其他</option>
          </select>
          <span @click="iterator = saveBtn(); iterator.next()" style="background-color: #df5000;color: #fff;height: auto;">保存模板</span>
        </div>
      </footer>
    </div>
    <div class="overlay" v-show="isShowPrompt">
      <div class="prompt-box">
        <header>
          <span>提示</span>
          <a href="javascript:;" @click="isShowPrompt = false">&times;</a>
        </header>
        <p>{{ promptText }}</p>
        <footer>
          <span @click="isShowPrompt = false; iterator && iterator.next()">确定</span>
          <span @click="isShowPrompt = false">取消</span>
        </footer>
      </div>
    </div>
  </div>
</template>

<script>
  import Data from '../../../../data.js'
  import Store from '../../../../store.js'
  export default {
    name: 'Templateedit',
    props: ['id', 'flag'],
    data () {
      return {
        index: '',
        value: null,
        templateType: '',
        quData: {},
        questions: [],
        questionTemplate: {},
        date: '',
        title: '',
        _title: '',
        topic: '',
        _topic: '',
        optionText: '',
        _optionText: '',
        curIndex: '',
        curOptIndex: '',
        promptText: '',
        iterator: {},
        isAdding: false,
        titleEditing: false,
        topicEditing: false,
        isShowPrompt: false,
        isShowDatepicker: false
      }
    },

    beforeRouterEnter (to, from, next) {
      let id = to.params.id
      let item = {}
      if (id !== 0) {
        let length = Store.fetch().length
        if (id < 0 || id > length) {
          alert('非法路由')
          next('')
        } else {
          item = Store.fetch()[id - 1]
        }

        if (item.state === 0) {
          next()
        } else {
          alert('非法路由')
          next('/')
        }
      } else {
        next()
      }
    },

    created () {
      this.questionTemplate = Data.template
      this.getData()
    },

    computed: {
      tempTitle () {
        return this.title || this.quData.title
      }
    },

    methods: {
      getData () {
        let that = this
        console.log(this.quData)
        this.$http.post('/requirement/require/geteditsurvey', {id: this.id}).then(function (res) {
          console.log(res.data)
          if (res.data.message == 'success') {
            that.quData = res.data.data
            that.date = that.quData.endTime
            that.title = that.quData.title
            that.questions = that.quData.questions
          }
        })
      },

      beforeRouterEnter (to, from, next) {
        let id = to.params.id
        let item = {}
        if (id !== 0) {
          let length = Store.fetch().length
          if (id < 0 || id > length) {
            alert('非法路由')
            next('')
          } else {
            item = Store.fetch()[id - 1]
          }

          if (item.state === 0) {
            next()
          } else {
            alert('非法路由')
            next('/')
          }
        } else {
          next()
        }
      },

      changeDate (date) {
        let nowTime = Date.now()
        let chioceTime = new Date(date.replace(/-/g, ',')) * 1
        if (chioceTime < nowTime) {
          this.iterator = null
          this.showPrompt(`选择的日期不能早于当前日期，请重新选择！`)
          return
        }
        this.date = date
      },

      cancelTitleEdit () {
        this.titleEditing = false
        this.title = this._title
      },

      cancelTopicEdit () {
        this.topicEditing = false
        this.topic = this._topic
      },

      cancelOptionEdit () {
        this.curOptIndex = ''
        this.optionText = this._optionText
      },

      doneTopicEdit (index) {
        this.topicEditing = false
        this.questions[index].topic = this.topic
      },

      doneOptionEdit (index, optIndex) {
        this.curOptIndex = ''
        this.questions[index].options[optIndex] = this.optionText
      },

      switchItem (index, array) {
        this.optIndex = ''
        this.curIndex = ''
        let arr = array.splice(index, 2)
        array.splice(index, 0, ...arr.reverse())
      },

      moveUp (index, array) {
        this.switchItem(index - 1, array)
      },

      moveDown (index, array) {
        this.switchItem(index, array)
      },

      errorPrompt (text) {
        this.iterator = null
        this.showPrompt(text)
      },

      reuse (index) {
        if (this.questions.length === 10) {
          this.errorPrompt(`每份问卷至多10个问题！`)
          return
        }
        let oldQuestion = this.questions[index]
        let newQuestion = JSON.parse(JSON.stringify(oldQuestion))
        this.questions.splice(index, 0, newQuestion)
      },

      delQuestion (index) {
        if (this.questions.length <= 1) {
          this.errorPrompt(`每份问卷至少一个问题！`)
          return
        }
        this.questions.splice(index, 1)
      },

      delOption (index, options) {
        if (options.length <= 2) {
          this.errorPrompt(`每个问题至少两个选项`)
          return
        }
        options.splice(index, 1)
      },

      addOption (options) {
        if (options.length === 4) {
          this.errorPrompt(`每个问题至多四个选项`)
          return
        }
        options.push(`请编辑选项内容`)
      },

      showPrompt (text) {
        this.promptText = text
        this.isShowPrompt = true
      },

      addType (type) {
        let temp = this.deepClone(this.questionTemplate[type])
        if (type == 'radio' || type == 'checkbox') {
          var options = temp.options
          var option_arr = []
          for (let i in options) {
            option_arr.push(options[i]) // 属性
          }
          temp.options = option_arr
        }
        console.log(temp)
        this.questions.push(temp)
      },

      saveData () {
        if (this.questions.length < 1) {
          this.errorPrompt(`每份问卷至少一个问题！`)
          return
        }

        this.quData.title = this.title
        this.quData.time = this.date
        this.quData.questions = this.questions
      },

      *backBtn () {
        yield this.showPrompt(`确认未保存回到主页吗？`)
        this.$emit('listenToEdit', 'back')
      },

      *saveBtn () {
        let that = this
        yield that.showPrompt(`确认要修改模板？`)
        that.saveData()
        that.quData.id = that.id
        if (that.flag == 21) {
          that.quData.type = 21
        } else {
          that.quData.type = that.templateType
        }
        console.log(that.quData)
        that.$http.post('/requirement/require/editsurvey', {
          quData: that.quData
        }).then((res) => {
          // console.log(res.data);
          if (res.data.message == 1) {
            alert('模板修改成功！')
            if (that.flag == 21) {
              this.$emit('listenToEdit', 'edit')
            } else {
              this.$router.push({ path: '/requirement/templatelist'})
            }
          } else {
            alert('模板修改失败！')
          }
        })
      },

      *releaseBtn () {
        yield this.showPrompt(`确认要保存并发布问卷？`)
        yield (() => {
          this.quData.state = 1
          this.quData.stateName = '发布中'
          this.saveData()
        })()
        yield this.$router.push({path: '/'})
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

    directives: {
      focus: {
        update: el => el.focus()
      }
    },

    watch: {
      '$route' (to, from) {
        this.$router.go(0)
      }
    }
  }

</script>

<style scoped lang="scss">
  @import '../../../../common/css/override-element-ui.css';
  @import '../../../../style/_Edit';
</style>
