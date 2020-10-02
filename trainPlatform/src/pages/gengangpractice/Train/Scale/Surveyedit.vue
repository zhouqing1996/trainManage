<template>
  <div class="container">
    <div class="qu-wrap">
      <header>
        <span @click="iterator = backBtn(); iterator.next()"><i class="iconfanhui2"></i> 返回</span>
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
            <!--<input type="text" @focus="_topic = topic;" v-model="topic" @blur="doneTopicEdit(index)">-->
            <input type="text"
                   v-focus
                   v-model="topic"
                   @focus="topic = item.topic"
                   @blur="blurTopic(index);"
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
                :class="{optionEditing: curOptIndex === optIndex}">
							<span class="optionText"
                    @click="curIndex=index; curOptIndex=optIndex; topicEditing = false">{{ option }}</span>
              <input type="text"
                     v-focus
                     v-model="optionText"
                     @focus="optionText = option"
                     @blur="blurOption(index,optIndex);"
                     @keyup.esc="cancelOptionEdit()"
                     @keyup.enter="doneOptionEdit(index, optIndex)">

              <ul class="opt-ctrl">
                <li v-if="optIndex !== 0"
                    @click="moveUp(optIndex,item.options)"><i class="el-icon-arrow-up"></i>上移</li>
                <li v-if="optIndex !== item.options.length - 1"
                    @click="moveDown(optIndex,item.options)"><i class="el-icon-arrow-down"></i>下移</li>
                <li v-else @click="addOption(item.options)"><i class="el-icon-plus"></i>添加</li>
                <li @click="delOption(optIndex,item.options)"><i class="el-icon-delete"></i>删除</li>
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
            <li @click="reuse(index)"><el-button size="mini"><i class="el-icon-plus"></i>添加</el-button></li>
            <li @click="delQuestion(index)"><el-button size="mini"><i class="el-icon-delete"></i>删除</el-button></li>
          </ul>
          <div></div>
        </section>
        <div class="add-box">
          <p class="qu-type" :class="{expand: isAdding}">
            <span @click="addType('radio')"><i class="icondanxuanti" style="font-size: 16px;color: #00A5FE"></i>单选题</span>
            <span @click="addType('checkbox')"><i class="iconduoxuanti" style="font-size: 16px;color: #00A5FE"></i>多选题</span>
            <span @click="addType('input')"><i class="iconplus-blanksfill" style="font-size: 16px;color: #00A5FE"></i>填空题</span>
            <span @click="addType('rate')"><i class="iconxingxing" style="font-size: 16px;color: #00A5FE"></i>打分题</span>
            <span @click="addType('textarea')"><i class="iconwenben1" style="font-size: 16px;color: #00A5FE"></i>文本题</span>
          </p>

          <p class="add-btn" @click="isAdding = !isAdding;">
            <span>+ 添加问题</span>
          </p>
        </div>
      </div>
      <footer>
        <div>
          <template>
            <div class="block">
              <span class="demonstration" style="color: #00A6FF">截止回收时间</span>
              <el-date-picker v-model="date" type="date" placeholder="选择日期" value-format="yyyy-MM-dd">
              </el-date-picker>
            </div>
          </template>
        </div>
        <div v-if="flag == 20" class="ctrl-part">
          <span @click="iterator = saveBtn(); iterator.next()">保存量表</span>
        </div>
        <div v-if="flag != 20" class="ctrl-part">
          <span @click="iterator = saveBtn(); iterator.next()" style="background-color: #df5000;color: #fff;height: auto;">保存问卷</span>
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
    name: 'Surveyedit',
    props: ['id', 'flag', 'row'],
    data () {
      return {
        index: '',
        value: null,
        quData: {},
        questions: [],
        questionTemplate: {},
        quList: Store.fetch(),
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
        isShowDatepicker: false,
        pickerOptions: {
          disabledDate (time) {
            return time.getTime() > Date.now()
          }
        }
      }
    },

    created () {
      this.user = this.$store.state.mutations.user
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
        if (this.id === 0) {
          let item = {}
          item.title = `问卷调查`
          item.state = 0
          item.stateName = '未发布'
          this.quData = item
          this.date = this.quData.time
          this.title = this.quData.title
          this.index = this.id
          this.questions = []
          this.questionTemplate = Data.template
        } else {
          let that = this
          console.log(this.quData)
          this.$http.post('/requirement/require/geteditsurvey', {id: this.id}).then(function (res) {
            console.log(res.data)
            if (res.data.message == 'success') {
              that.quData = res.data.data
              that.quData.id = that.id
              that.date = that.quData.endTime
              that.title = that.quData.title
              that.questions = that.quData.questions
            }
          })
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

      blurTopic (index) {
        this.curIndex = ''
        this.questions[index].topic = this.topic
        this.topic = ''
      },

      blurOption (index, optIndex) {
        this.curIndex = ''
        this.questions[index].options[optIndex] = this.optionText
        this.optionText = ''
        this.curOptIndex = ''
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
        console.log(index)
        console.log(array)
        this.switchItem(index - 1, array)
      },

      moveDown (index, array) {
        console.log(index)
        console.log(array)
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
        this.questions.push(temp)
      },

      saveData () {
        if (this.questions.length < 1) {
          this.errorPrompt(`每份问卷至少一个问题！`)
          return
        }

        this.quData.title = this.title
        console.log(this.date)
        this.quData.time = this.date
        this.quData.questions = this.questions
        console.log(this.quData.questions)
      },

      *backBtn () {
        yield this.showPrompt(`确认未保存回到主页吗？`)
        this.$emit('listenToEdit', 'back')
      },

      *saveBtn () {
        let that = this
        this.saveData()
        console.log(that.flag)
        if (that.flag == 10) {
          that.quData.type = 10
        } else if (that.flag == 20) {
          that.quData.type = 20
        } else {
          that.quData.type = 0
        }

        if (!that.quData.time) {
          that.quData.time = 'unlimited'
        }
        if (that.flag == 20) {
          yield that.showPrompt(`确认要保存量表？`)
        } else {
          yield that.showPrompt(`确认要保存问卷？`)
        }
        if (that.quData.id == undefined) {
          that.$http.post('/requirement/require/insertsurvey2', {
            quData: that.quData,
            username: that.user,
            recruitId: that.row.id
          }).then((res) => {
            console.log(res.data)
            if (res.data.message == 1) {
              if (that.flag == 10) {
                alert('问卷添加成功！')
                that.$router.push({ path: '/tracking/tracksurvey'})
              } else if (that.flag == 20) {
                alert('量表添加成功！')
                that.$emit('listenToEdit', 'add')
              } else {
                alert('问卷添加成功！')
                that.$router.push({ path: '/requirement/surveylist'})
              }
            } else {
              alert('问卷添加失败！')
            }
          })
        } else {
          that.$http.post('/requirement/require/editsurvey2', {
            quData: that.quData,
            username: that.user
          }).then((res) => {
            console.log(res.data)
            if (res.data.message == 1) {
              if (that.flag == 10) {
                alert('问卷修改成功！')
                that.$router.push({ path: '/tracking/tracksurvey'})
              } else if (that.flag == 20) {
                alert('量表修改成功！')
                that.$emit('listenToEdit', 'edit')
              } else {
                alert('问卷修改成功！')
                that.$router.push({ path: '/requirement/surveylist'})
              }
            } else {
              alert('问卷修改失败！')
            }
          })
        }
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
