<template>
  <div class="container">
    <div class="qu-wrap">
      <header>
        <span  @click="iterator = backBtn(); iterator.next()" ><i class="iconfanhui2"></i> 返回</span>
        <p>{{ quData.title }}</p>
      </header>
      <div class="qu-content">
        <section class="qu-item"
                 v-for="(item, index) in questions" :key="index"
                 :class="{nowEditing: curIndex === index && topicEditing, optEditing: curIndex === index}">
          <h3 v-if="item.type === 'input1'" @click="curIndex = index; curOptIndex=''; topicEditing = true">
            <span class="qu-num">{{`Q${index + 1}`}}</span>
            <span class="qu-topic">{{ item.topic }}</span>
            <input type="text"
                   v-model="topic"
                   @focus="topic = item.topic"
                   @blur="blurTopic(index);">
          </h3>
          <h3 v-if="item.type === 'input'">{{ `Q${index + 1}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;${item.topic}` }}
            <span v-if="item.isMandatory" style="color: #E74C3C"> *</span>
          </h3>
          <template >
           <input style="margin-left: 40px" v-model="r[index]" :required="item.isMandatory" @blur="inputAnswer(r[index],item.id,index)">
          </template>
          <!--<template v-if="item.type === 'input1'">-->
            <!--<input style="margin-left: 40px" type="text">-->
          <!--</template>-->
          <div></div>
        </section>
      </div>
      <div class="add-box">
        <p class="qu-type" :class="{expand: isAdding}">
          <span @click="addType('input1')"><i class="iconplus-blanksfill" style="font-size: 16px;color: #00A5FE"></i>填空题</span>
        </p>

        <p class="add-btn" @click="isAdding = !isAdding;">
          <span>+ 添加问题</span>
        </p>
      </div>
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
  import Store from '../../../store.js'
  import { common } from '../../../main'
  import Data from '../../../data.js'

  export default {
    name: 'Demandview',
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
        status:'',
        isAdding: false,
        curIndex: '',
        curOptIndex: '',
        topicEditing: false,
        topic: ''
      }
    },

    created() {
      // this.username = localStorage.getItem("user");
      this.user = this.$store.state.mutations.user;
      this.id = this.$route.query.id;
      this.flag = this.$route.query.flag;
      this.status = this.$route.query.status;
      this.stuId = this.$route.query.stuId;
      this.questionTemplate = Data.template;
      this.getData();
    },

    methods: {
      getData() {
        let that = this;
        console.log(that.id);
        this.$http.post('/requirement/require/geteditsurvey',{id:that.id}).then(function (res) {
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

      inputAnswer(text,queId,index){
        console.log(index,text,queId);
        // if (this.answer[index]==undefined){
        //   this.answer[index]=[];
        // }
        this.answer.push(text);
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

      addType(type) {
        let temp=this.deepClone(this.questionTemplate[type]);
        this.questions.push(temp);
      },

      blurTopic(index){
        this.curIndex='';
        this.questions[index].topic=this.topic;
        this.topic = '';
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
            }else {
              text = `确认提交问卷吗？`
            }
          }
          yield that.showPrompt(text);
          var ip = localStorage.getItem('Ip');
          var cityname = localStorage.getItem('cityname');
          console.log(this.questions)
          console.log(this.answer)
          console.log(this.stuId)
          that.$http.post('/apprenticeship/require/submit',{
            username:that.user,
            id:that.id,
            answer: that.answer,
            questions: that.questions,
            ip:ip,
            cityname:cityname,
            stuId:that.stuId
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
      },
      deepClone(data){
        var type = typeof data;
        var obj;
        if(type === 'array'){
          obj = [];
        } else if(type === 'object'){
          obj = {};
        } else {
          //不再具有下一层次
          return data;
        }
        if(type === 'array'){
          for(var i = 0, len = data.length; i < len; i++){
            obj.push(this.deepClone(data[i]));
          }
        } else if(type === 'object'){
          for(var key in data){
            obj[key] = this.deepClone(data[key]);
          }
        }
        return obj;
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
