<template>
  <div class="container">
    <div class="qu-wrap">
      <header>
        <span  @click="iterator = backBtn(); iterator.next()" ><i class="iconfanhui2"></i> 返回</span>
        <p>{{ quData.title }}</p>
      </header>
      <div class="qu-content">
        <section class="qu-item" v-for="(item, index) in questions">
          <h3>{{ `Q${index + 1}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;${item.topic}` }}
            <span v-if="item.isMandatory" style="color: #E74C3C"> *</span>
          </h3>
          <template >
            <input style="margin-left: 40px" v-model="r[index]" v-if="item.type === 'input'" :required="item.isMandatory" @blur="inputAnswer(r[index],item.id)">
          </template>
          <template style="margin-left: 40px">
            <el-rate v-if="item.type === 'rate'"  v-model="r[index]" @change="rateAnswer(r[index],item.id)"></el-rate>
          </template>
          <textarea rows="8" cols="80" v-model="r[index]" v-if="item.type === 'textarea'" :required="item.isMandatory" @blur="textareaAnswer(r[index],item.id)">
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
  import Store from '../../store.js'
  import { common } from '../../main'

  export default {
    name: 'Surveyview',
    data() {
      return {
        id:'',
        r:[],
        user:'',
        value:null,
        quData:{},
        queId:'',
        questions: [],
        inputText:'',
        answerText:'',
        answer:[],
        answers: {},
        promptText: '',
        isShowPrompt: false,
        status:''
      }
    },

    created() {
      // this.username = localStorage.getItem("user");
      this.user = this.$store.state.mutations.user;
      this.id = this.$route.query.id;
      this.flag = this.$route.query.flag;
      this.status = this.$route.query.status;
      this.stuId = this.$route.query.stuId;
      // console.log(this.status)
      this.getData();
    },

    methods: {
      getData() {
        let that = this;
        console.log(that.id);
        this.$http.post('/requirement/require/geteditsurvey',{id:that.id}).then(function (res) {
          // console.log(res.data);
          if (res.data.message == 'success') {
            that.quData = res.data.data;
            that.questions=that.quData['questions'];
          }
        })
      },

      showPrompt(text) {
        this.promptText = text;
        this.isShowPrompt = true;
      },

      radioAnswer(event, index, queId) {
        if (this.answer[queId]==undefined){
          this.answer[queId]=[];
        }
        console.log(index,queId);
        let that = this;
        if (event.target.checked) {
          that.answer[queId]=index;
        }
      },

      checkboxAnswer(event, index, queId) {
        console.log(this.answer[queId]);
        if (this.answer[queId]==undefined){
          this.answer[queId]=[];
        }
        let that = this;
        if (event.target.checked) {
          that.answer[queId].push(index);
        }
        else {
          that.answer[queId].splice(that.answer[queId].indexOf(index), 1);
        }
      },

      inputAnswer(text,queId){
        if (this.answer[queId]==undefined){
          this.answer[queId]=[];
        }
        this.answer[queId].push(text);
      },

      textareaAnswer(text,queId){
        console.log(this.answer[queId]);
        if (this.answer[queId]==undefined){
          this.answer[queId]=[];
        }
        this.answer[queId].push(text);
      },

      rateAnswer(value,queId){
        if (this.answer[queId]==undefined){
          this.answer[queId]=[];
        }
        this.r.index =value;
        this.answer[queId] = value;
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

      requireValidate() {
        let that = this;
        var isMandatory;
        var queId;
        var type;
        for (var i=0;i<that.questions.length;i++) {
          queId = that.questions[i]['id'];
          isMandatory = that.questions[i]['isMandatory'];
          type = that.questions[i]['type'];
          if (isMandatory == 1){
            console.log(that.answer[queId])
            if (that.answer[queId] == undefined){
              return false;
            }
          }
        }
        return true;
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

      sendAnswer() {
        // this.getAnswer();
        // this.$router.push({path: '/'});
        // console.log('非正式项目，无需发送用户回答数据，打印出来看看就好');
        // console.log(this.answers);
      },

      *submitBtn() {
        let that = this;
        let text = ``;
        let curTime = Date.now();
        let endTime = '';
        endTime = new Date(that.quData['endTime'].replace(/-/g, ','))*1;
        if (that.stuId == undefined&&that.flag==20){
          text = `量表不可在此填写！`;
          yield that.showPrompt(text);
        }else{
          if (endTime < curTime) {
            text = `此调查已截止！`;
            that.iterator = null;
          }else{
            if (that.status == 1) {
              text = `问卷尚未发布，无法提交！`;
              that.iterator = null;
            }else if (!that.requireValidate()) {
              text = `有必填项未填写，无法提交！`;
              that.iterator = null;
            } else {
              text = `确认提交问卷吗？`
            }
          }
          yield that.showPrompt(text);
          var ip = localStorage.getItem('Ip');
          var cityname = localStorage.getItem('cityname');
          console.log(this.answer)
          that.$http.post('/requirement/require/submit',{
            username:that.user,
            id:that.id,
            answer: that.answer,
            ip:ip,
            cityname:cityname,
            stuId:this.stuId
          }).then(function (res) {
            console.log(res.data);
            if (that.flag == 20){
              if(res.data.message==1){
                alert("问卷量表成功！");
                // this.$router.push({ path:'/requirement/surveylist'});
              }else{
                alert("问卷量表失败！");
              }
            } else{
              if(res.data.message==1){
                alert("问卷填写成功！");
                // this.$router.push({ path:'/requirement/surveylist'});
              }else{
                alert("问卷不能重复填写！");
              }
            }
          })
        }

      },

      *backBtn() {
        yield this.showPrompt(`放弃答题回到主页吗？`);
        console.log(this.flag)
        if (this.flag == 10){
          yield this.$router.push({path:'/tracking/tracksurvey'});
        }else if (this.flag == 20){
          yield this.$router.push({path:'/practice/teacher',query:{table:1}});
        } else if (this.flag == 40){
          yield this.$router.push({path:'/apprenticeship/enroll/demandlist'});
        }else{
          yield this.$router.push({path:'/requirement/surveylist'});
        }
      }
    },
    watch: {
      '$route' (to, from) {
        this.$router.go(0);
      }
    }
  }
</script>

<style scoped lang="scss">
  @import '../../style/_Fill.scss';
  @import '../../common/css/override-element-ui.css';
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
