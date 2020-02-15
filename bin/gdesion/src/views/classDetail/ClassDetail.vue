<template>
  <div class="classDetail">
    <Head></Head>
    <div class="classContent">
      <div class="imgRow">
        <img class="classImg" :src="require(`../../../../../src/${cur.image}`)" alt />
        <div class="imgTitle">{{cur.curName}}</div>
      </div>
      <div class="classMain">
        <div class="classTitle">{{cur.curName}}</div>
        <div class="classDetail" :title="cur.curDetail">{{cur.curDetail}}</div>
        <div class="classTeacher">
          <img src="../../assets/teacher.png" alt />
          <p>{{cur.curTeacher}}</p>
        </div>
        <div class="classPaid">
          <div class="paid_least">
            自身要有
            <span>{{cur.least}}</span>积分
          </div>
        </div>
        <div class="classTime">
          <p>共100小时 |</p>
          <p>添加时间: {{cur.curBeginTime}} |</p>
          <p>截止时间: {{cur.curEndTime}} |</p>
          <p class="evaluate">评分: {{cur.evaluate}}分</p>
        </div>
        <div class="classBtn">
          <el-button type="primary" @click="sureSelect" v-if="choose">开始学习</el-button>
          <el-button type="primary" disabled @click="sureSelect" v-else>正在学习</el-button>

          <el-button type="danger" @click="$router.push('indexStu')">返回上级</el-button>
        </div>
      </div>
    </div>
    <div class="Stuevaluate">
      <h2>学生评价</h2>
      <div v-if="evaluate[0]!=null" class="StuevaluateRow">
        <ul>
          <li v-for="item in evaluate">
            <p>匿名 <span class="evaTime">{{item.StuEvaluateTime}}</span></p>
            <p class="evaText">{{item.StuEvaluate}}</p>
          </li>
        </ul>
      </div>
      <div class="evaNone" v-else>
        目前还没有学生评论
      </div>
    </div>
  </div>
</template>

<script>
import Head from "../../components/head/Head.vue";
import moment from "moment";
import { myAxios } from "../../request/index.js";

export default {
  data() {
    return {
      headTitle: "课程详情",
      cur: JSON.parse(sessionStorage.getItem("class")),
      user: JSON.parse(sessionStorage.getItem("user")),
      choose: true,
      evaluate: []
    };
  },
  async created() {
    if (!sessionStorage.getItem("user")) {
      this.$message.error("检测到异常登录，请登录");
      setTimeout(() => {
        this.$router.push("/");
      }, 2000);
      return;
    }
    if (sessionStorage.getItem("user")) {
      const res = await myAxios(this, {
        type: "judgeChoose",
        curID: this.cur.curID,
        stuID: this.user
      });
      if (res.code === "chooseTru") {
        this.choose = false;
      }
      var evaluates = res.data.filter(v => {
        return v.StuEvaluate != null;
      });
      this.evaluate = evaluates;
      console.log(res.data);
    }
  },
  methods: {
    async sureSelect() {
      var checked = confirm(
        "确认要报名吗，在一天之内可以取消本课程，超过一天再取消课程会扣除3点积分。"
      );
      if (checked) {
        var timeB = moment().format("YYYY-MM-DD-HH:mm:ss");
        var timeE = moment()
          .add(1, "days")
          .format("YYYY-MM-DD-HH:mm:ss");
        const res = await myAxios(this, {
          type: "chooseCur",
          curID: this.cur.curID,
          user: this.user,
          timeB,
          timeE
        });
        if (res.code == 1020) {
          alert(res.msg);
          this.choose = false;
        } else {
          alert(res.msg);
        }
      }
    }
  },
  components: {
    Head
  }
};
</script>

<style lang='less'>
.classContent {
  width: 1400px;
  background-color: #fff;
  margin: 30px auto;
  padding: 30px 0 0 30px;
  display: flex;
  justify-content: space-around;
  .imgRow {
    width: 448px;
    height: 260px;
    background-color: #a1c8e5;
    border-radius: 10px 10px 10px 10px;
    .classImg {
      width: 100%;
      height: 200px;
      border-radius: 10px 10px 0 0;
    }
    .imgTitle {
      height: 60px;
      text-align: center;
      line-height: 60px;
      font-size: 26px;
      color: #333;
      opacity: 0.8;
    }
  }
  .classMain {
    width: 900px;
    height: 400px;
    padding-left: 30px;
    display: flex;
    flex-direction: column;
    justify-content: space-around;
    color: #6f6f6f;
    letter-spacing: 1px;
    .classTitle {
      font-size: 34px;
      font-weight: 500;
      color: #333;
    }
    .classPaid {
      span {
        font-size: 26px;
        color: orangered;
        margin: 0 5px;
      }
    }
    .classTeacher {
      display: flex;
      position: relative;
      font-size: 26px;
      margin-bottom: 30px;
      img {
        width: 35px;
        height: 35px;
      }
      p {
        position: absolute;
        left: 45px;
      }
    }
    .classTime {
      display: flex;
      p {
        margin-right: 5px;
      }
      .evaluate {
        color: #0087cf;
      }
    }
  }
}
.Stuevaluate {
  width: 1400px;
  background-color: #fff;
  border-radius: 10px;
  margin: 30px auto;
  padding: 30px 0 0 30px;
  h2 {
    font-size: 34px;
    font-weight: 500;
    color: #333;
  }
  .StuevaluateRow{
    margin: 30px 0 0 50px;
    ul{
      padding-bottom: 30px;
      li{
      margin-bottom: 20px;
      border-bottom: 1px solid #ccc;
      p{
        margin-bottom: 10px;
        .evaTime{
          margin-left: 10px;
          color: #87898a;
        }
      }
      .evaText{
        color: #87898a;
        margin-left: 20px;
      }
    }
    }
  }
  .evaNone{
    font-size: 28px;
    margin: 30px 0 0 30px;
    padding-bottom: 30px;
    color: #ccc;
  }
}
</style>
