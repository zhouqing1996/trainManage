<template>
  <div class="container">
    <div class="qu-wrap">
      <header>
        <p>{{ quData.title }}</p>
      </header>
      <div class="qu-content">
        <section class="qu-item" v-for="(item, index) in questions">
          <h3>{{ `Q${index + 1}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;${item.topic}` }}
            <span v-if="item.isMandatory" style="color: #E74C3C"> *</span>
          </h3>
          <template >
            <input v-model="r[index]" v-if="item.type === 'input'" :required="item.isMandatory" @blur="inputAnswer(r[index],item.id)">
          </template>
          <template>
            <el-rate v-if="item.type === 'rate'"  v-model="r[index]" @change="rateAnswer(r[index],item.id)"></el-rate>
          </template>
          <textarea rows="4" style="width: 100%" v-model="r[index]" v-if="item.type === 'textarea'" :required="item.isMandatory" @blur="textareaAnswer(r[index],item.id)">
          </textarea>

          <ul v-if="item.type === 'radio'||item.type === 'checkbox'" class="options-list">
            <li v-for="(option, optIndex) in item.options">
              <label >
                <input v-if="item.type === 'radio'"
                       :type="item.type"
                       :name="index + 1"
                       @change="radioAnswer($event, optIndex, item.id)">
                <input v-if="item.type === 'checkbox'"
                       :type="item.type"
                       :name="index + 1"
                       @change="checkboxAnswer($event, optIndex, item.id)">
                <span>{{ option }}</span>
              </label>
            </li>
          </ul>
          <div></div>
        </section>
      </div>
      <!--<footer >-->
      <!--<span class="submitButton" @click="iterator = submitBtn(); iterator.next()">提交问卷</span>-->
      <!--</footer>-->
      <footer>
        <span v-if="flag == 20" id="submitBtn" @click="iterator = submitBtn(); iterator.next()">提交量表</span>
        <span v-else id="submitBtn" @click="iterator = submitBtn(); iterator.next()">提交问卷</span>
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
  export default {
    name: 'Surveyviewnew',
    data () {
      return {
        id: '',
        r: [],
        user: '',
        value: null,
        quData: {},
        queId: '',
        questions: [],
        inputText: '',
        answerText: '',
        answer: [],
        answers: {},
        promptText: '',
        isShowPrompt: false,
        status: ''
      }
    },

    created () {
      // this.username = localStorage.getItem("user");
      this.user = this.$store.state.mutations.user
      this.id = this.$route.query.id
      console.log(this.id, this.status)
      this.flag = this.$route.query.flag
      this.status = this.$route.query.status
      this.stuId = this.$route.query.stuId
      // console.log(this.status)
      this.getData()
    },

    methods: {
      getData () {
        let that = this
        console.log(that.id)
        this.$http.post('/requirement/require/geteditsurvey', {id: that.id}).then(function (res) {
          // console.log(res.data);
          if (res.data.message == 'success') {
            that.quData = res.data.data
            that.questions = that.quData['questions']
          }
        })
      },

      showPrompt (text) {
        this.promptText = text
        this.isShowPrompt = true
      },

      radioAnswer (event, index, queId) {
        if (this.answer[queId] == undefined) {
          this.answer[queId] = []
        }
        console.log(index, queId)
        let that = this
        if (event.target.checked) {
          that.answer[queId] = index
        }
      },

      checkboxAnswer (event, index, queId) {
        console.log(this.answer[queId])
        if (this.answer[queId] == undefined) {
          this.answer[queId] = []
        }
        let that = this
        if (event.target.checked) {
          that.answer[queId].push(index)
        } else {
          that.answer[queId].splice(that.answer[queId].indexOf(index), 1)
        }
      },

      inputAnswer (text, queId) {
        if (this.answer[queId] == undefined) {
          this.answer[queId] = []
        }
        this.answer[queId].push(text)
      },

      textareaAnswer (text, queId) {
        console.log(this.answer[queId])
        if (this.answer[queId] == undefined) {
          this.answer[queId] = []
        }
        this.answer[queId].push(text)
      },

      rateAnswer (value, queId) {
        if (this.answer[queId] == undefined) {
          this.answer[queId] = []
        }
        this.r.index = value
        this.answer[queId] = value
      },

      // requireValidate() {
      //   let textareas = document.querySelectorAll('textarea');
      //   return [].every.call(textareas, item => {
      //     if (item.hasAttribute('required') && item.value.trim() === '') {
      //       return false;
      //     }
      //     return true;
      //   })
      // },

      requireValidate () {
        let that = this
        var isMandatory
        var queId
        var type
        for (var i = 0; i < that.questions.length; i++) {
          queId = that.questions[i]['id']
          isMandatory = that.questions[i]['isMandatory']
          type = that.questions[i]['type']
          if (isMandatory == 1) {
            console.log(that.answer[queId])
            if (that.answer[queId] == undefined) {
              return false
            }
          }
        }
        return true
        // let textareas = document.querySelectorAll('textarea');
        // return [].every.call(textareas, item => {
        //   if (item.hasAttribute('required') && item.value.trim() === '') {
        //     return false;
        //   }
        //   return true;
        // })
      },

      // getAnswer() {
      //   this.questions.forEach((item, index) => {
      //     this.answers[`Q${index + 1}answer`] = item.answer;
      //   })
      // },

      sendAnswer () {
        // this.getAnswer();
        // this.$router.push({path: '/'});
        // console.log('非正式项目，无需发送用户回答数据，打印出来看看就好');
        // console.log(this.answers);
      },

      *submitBtn () {
        let that = this
        let text = ``
        let curTime = Date.now()
        let endTime = ''
        endTime = new Date(that.quData['endTime'].replace(/-/g, ',')) * 1
        if (that.stuId == undefined && that.flag == 20) {
          text = `量表不可在此填写！`
          yield that.showPrompt(text)
        } else {
          if (endTime < curTime) {
            text = `此调查已截止！`
            that.iterator = null
          } else {
            if (that.status == 1) {
              text = `问卷尚未发布，无法提交！`
              that.iterator = null
            } else if (!that.requireValidate()) {
              text = `有必填项未填写，无法提交！`
              that.iterator = null
            } else {
              text = `确认提交问卷吗？`
            }
          }
          yield that.showPrompt(text)
          var ip = localStorage.getItem('Ip')
          var cityname = localStorage.getItem('cityname')
          console.log(this.stuId)
          that.$http.post('/requirement/require/submit', {
            username: that.user,
            id: that.id,
            answer: that.answer,
            ip: ip,
            cityname: cityname,
            stuId: this.stuId
          }).then(function (res) {
            console.log(res.data)
            if (that.flag == 20) {
              if (res.data.message == 1) {
                alert('问卷量表成功！')
                // this.$router.push({ path:'/requirement/surveylist'});
              } else {
                alert('问卷量表失败！')
              }
            } else {
              if (res.data.message == 1) {
                alert('问卷填写成功！')
                // this.$router.push({ path:'/requirement/surveylist'});
              } else {
                alert('问卷不能重复填写！')
              }
            }
          })
        }
      },

      *backBtn () {
        yield this.showPrompt(`放弃答题回到主页吗？`)
        console.log(this.flag)
        if (this.flag == 10) {
          yield this.$router.push({path: '/tracking/tracksurvey'})
        } else if (this.flag == 20) {
          yield this.$router.push({path: '/practice/teacher', query: {table: 1}})
        } else {
          yield this.$router.push({path: '/requirement/surveylist'})
        }
      },
      GetQueryString (name) {
        let reg = new RegExp('(^|&)' + name + '=([^&]*)(&|$)')
        let r = window.location.search.substr(1).match(reg)
        if (r != null) return unescape(r[2]); return null
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
  .qu-wrap > header {
    p:hover {
      background-color: #fff;
    }
  }

  $white: #fff;
  $orange: #f07600;
  $deep-orange: #c26206;
  $light-orange: #fcf0e5;
  $light-green: #60e451;
  $font-color: #555;

  @mixin flex-center {
    display: flex;
    justify-content: center;
    align-items: center;
  }

  @mixin wrap-background {
    border-radius: 5px;
    background-color: $white;
    box-shadow: 0 0 5px #ccc;
  }

  @mixin add-btn {
    cursor: pointer;
    color: $white;
    border: 1px solid $deep-orange;
    border-radius: 5px;
    background-color: $orange;

    &:hover {
      box-shadow: 0 0 8px $orange;
    }

  }

  @mixin nomal-btn {
    span {
      display: inline-block;
      width: 7.5rem;
      font-size: 1.2rem;
      cursor: pointer;
      text-align: center;
      border: 1px solid #ccc;
      border-radius: 3px;
      background-color: $white;

      &:hover {
        color: $white;
        border-color: $deep-orange;
        background-color: $orange;
        box-shadow: 0 0 8px $orange;
      }

    }

  }

  @mixin check-icon {
    i {
      display: inline-block;
      box-sizing: border-box;
      width: 1.2rem;
      height: 1.2rem;
      border: 1px solid #999;
      border-radius: 50%;
      background-color: #eee;

      &.checked {
        border: .4rem solid $orange;
      }

    }

  }

  .show {
    display: block;
  }

  .overlay {
    position: fixed;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    background-color: rgba(0, 0, 0, .2);

    .prompt-box {
      position: fixed;
      top: 50%;
      left: 50%;
      width: 32rem;
      height: 18rem;
      transform: translate(-50%, -50%);
      @include wrap-background();

      header {
        display: flex;
        height: 4rem;
        padding: 0 2rem;
        font-weight: 700;
        line-height: 4rem;
        background-color: #f2f2f2;
        justify-content: space-between;

        a {
          color: #555;

          &:hover {
            color: $orange;
          }

        }

      }

      p {
        padding: 3rem 2rem 4rem 2rem;
      }

      footer {
        text-align: center;
        @include nomal-btn;

        span {
          width: 7rem;
          margin: .5rem;

          &:nth-of-type(1) {
            color: #fff;
            border-color: $deep-orange;
            background-color: $orange;
          }

          &:nth-of-type(2) {
            color: $font-color;
            background-color: #fff;

            &:hover {
              border-color: #ccc;
              box-shadow: 0 0 8px #ccc;
            }

          }

        }

      }

    }

  }

  .container {
    width: 100%;
    padding: 0 !important;
    margin-bottom: 6rem;
    color: #555;
  }

  .qu-wrap {
    box-sizing: border-box;
    padding: 2rem;
    @include wrap-background;
  }

  .qu-wrap > header {
    position: relative;
    height: 5rem;
    margin: 0  2rem 2rem 2rem;
    line-height: 5rem;
    text-align: center;

    span {
      position: absolute;
      top: 0;
      left: 0;
      cursor: pointer;
    }

    span:hover {
      color: $orange;
    }

    p, input {
      width: 100%;
      margin: 0 auto;
      font-size: 1.8rem;
      font-weight: 700;
    }

    p:hover {
      background-color: $light-orange;
    }

    input {
      display: none;
      height: 100%;
      text-align: center;
      border: none;
      outline: none;
      background-color: #ccc;

      &.inlineShow {
        display: inline-block;
      }

    }

  }

  .qu-content {
    border: {
      top: 2px solid #bbb;
      bottom: 2px solid #bbb;
    }

    .qu-item {
      position: relative;
      margin: 1rem 0;
      padding: 1rem 2rem;

      &:hover {
        background-color: $light-orange;

        .operat-list {
          display: flex;
        }

        div:last-of-type {
          display: none;
        }

        #require-check {
          display: block;
        }

      }

      h3 {
        font-size: 1.4rem;
        font-weight: 600;
        line-height: 2rem;

        & > input {
          display: none;
          width: 15rem;
          height: 2rem;
          font-size: 1.4rem;
          border: none;
          outline: none;
          background-color: #ccc;
        }

      }

      div:last-of-type {
        height: 2rem;
      }

      .qu-num {
        display: inline-block;
        width: 2.5rem;
        margin-right: 1rem;
      }

      &.nowEditing {
        .qu-topic {
          display: none;
        }

        h3 > input {
          display: inline-block;
        }

      }

      &.optEditing .optionEditing {
        .optionText {
          display: none;
        }

        & > input {
          display: inline-block;
        }

      }

    }

    .options-list > li {
      margin: 1rem 0 0 4rem;

      &:hover {
        ul {
          display: inline-flex;
          margin-left: 2rem;
        }

        li {
          margin-left: 1rem;
          font-size: 1.2rem;
          cursor: pointer;
        }

        li:hover {
          color: $orange;
        }

      }

      & > ul {
        display: none;
      }

      & > input {
        display: none;
        width: 15rem;
        height: 2rem;
        font-size: 1.4rem;
        border: none;
        outline: none;
        background-color: #ccc;
      }

    }

    textarea {
      resize: none;
    }

    //div{
    //  margin: 1rem 0 0 4rem;
    //  resize: none;
    //}

    .operat-list {
      display: none;
      height: 2rem;
      justify-content: flex-end;

      li {
        margin-right: 1rem;
        cursor: pointer;

        &:hover {
          color: $orange;
        }

      }

    }

  }

  .add-box {
    border: 1px solid #ccc;

    .qu-type, .add-btn {
      @include flex-center;
    }

    .qu-type {
      overflow: hidden;
      height: 0;
      transition: height .5s;

      &.expand {
        height: 7rem;
      }

      span {
        margin: 0 1rem;
        padding: .5rem 1.5rem;
        cursor: pointer;
        border: 1px solid #ccc;
        background-color: #eee;

        &:hover {
          background-color: #ccc;
        }

      }

    }

    .add-btn {
      padding: 2rem 0;
      font-size: 2rem;
      cursor: pointer;
      background-color: #eee;

      &:hover {
        background-color: #ccc;
      }

    }

  }

  .qu-wrap > footer {
    display: flex;
    padding: 2rem 8rem;
    justify-content: space-between;

    .date-part {
      position: relative;

      input {
        display: inline-block;
        box-sizing: border-box;
        width: 24rem;
        margin-left: 1.5rem;
        margin-top: 5px;
        padding: 1rem 2rem;
        font-size: 1.2rem;
        cursor: pointer;
        border: 1px solid #ccc;
        outline: none;
      }

    }

    .ctrl-part {
      @include nomal-btn();

      span {
        margin: 0 1rem;

        &:nth-of-type(2) {
          color: #fff;
          border-color: $deep-orange;
          background-color: $orange;
        }

        &:nth-of-type(1) {
          color: $font-color;
          background-color: #fff;

          &:hover {
            border-color: #ccc;
            box-shadow: 0 0 8px #ccc;
          }

        }

      }

    }

  }

  #date-picker {
    position: absolute;
    right: 0;
    margin-top: .5rem;
  }

  #require-check {
    position: absolute;
    top: 1rem;
    right: 2rem;
    display: none;
  }

  .submitButton{
    width: 200px;
    background-color: #df5000;
    text-align: center;
    color: #fff;
    padding: 10px 0px 10px 0px;
    border-radius: 9px;
    box-shadow: #969896;
  }
  .submitButton:hover{
    box-shadow: 3px 3px 3px #969896;
  }
</style>
