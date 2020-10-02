<template>
  <div class="container">
    <div class="qu-wrap">
      <header>
        <span  @click="iterator = backBtn(); iterator.next()" ><i class="iconfanhui2"></i> 返回</span>
        <p>{{ quData.title }}</p>
      </header>
      <div class="qu-content">
        <section class="qu-item" v-for="(item, index) in questions">
          <h3>{{ `Q${index + 1}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;${item.topic}` }}</h3>
          <template style="margin-left: 40px">
            <el-rate v-if="item.type === 'rate'" v-model="answers2[index]" show-score disabled></el-rate>
          </template>
          <textarea rows="8" cols="80" v-if="item.type === 'textarea'" v-model="answers2[index]" disabled>
          </textarea>

          <ul v-if="item.type === 'radio'||item.type === 'checkbox'" class="options-list">
            <li v-for="(option, optIndex) in item.options">
              <label >
                <span>{{ option }}</span>
              </label>
            </li>
          </ul>
          <div></div>
        </section>
      </div>
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
  import Store from '../../../store.js'
  import { common } from '../../../main'

  export default {
    name: 'Demandview',
    data() {
      return {
        id:'',
        r:[],
        flag:'',
        user:'',
        value:null,
        quData:{},
        queId:'',
        questions: [],
        inputText:'',
        answerText:'',
        answer:[],
        answers: {},
        answers2:[],
        promptText: '',
        isShowPrompt: false,
        status:''
      }
    },

    created() {
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
          if (res.data.message == 'success') {
            that.$http.post('/apprenticeship/require/getanswer',{id:that.id}).then(function (res1) {
              if (res1.data.message == 'success') {
                for (var i=0;i<res.data.data['questions'].length;i++) {
                  that.answers2[i]=0;
                  for (var j=0;j<res1.data.data.length;j++){
                    if (res1.data.data[j]['questionId']==i){
                      that.answers2[i] = res1.data.data[j]['content'];
                      that.quData = res.data.data;
                      that.questions=that.quData['questions'];
                    }else{
                    }
                  }
                }
                console.log(that.answers2)
              }
            })
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
      },

      *backBtn() {
        yield this.showPrompt(`放弃答题回到主页吗？`);
        yield this.$router.push({path:'/apprenticeship/enroll/demandlist'});
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
  @import '../../../style/_Fill.scss';
  @import '../../../common/css/override-element-ui.css';
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
