<template>
  <div class="indexStu">
    <Head></Head>
    <div class="stuContent">
      <div class="tabLeft">
        <ul>
          <li
            :class="tabNum == 0 ? 'allCurriculum1 pseudo' : 'allCurriculum'"
            @click="chooseTab(0)"
          >全部课程</li>
          <li :class="tabNum == 1 ? 'curriculum1 pseudo' : 'curriculum'" @click="chooseTab(1)">可选课程</li>
          <li
            :class="tabNum == 2 ? 'unCurriculum1 pseudo' : 'unCurriculum'"
            @click="chooseTab(2)"
          >不可选课程</li>
          <li :class="tabNum == 3 ? 'search1 pseudo' : 'search'" @click="chooseTab(3)">信息搜索</li>
        </ul>
      </div>
      <div class="tabRightIndex" v-if="tabNum==3">
        <h2>信息搜索</h2>
        <el-input placeholder="请输入内容" v-model="selectInput" class="input-with-select">
          <el-select v-model="selectTab" slot="prepend" placeholder="请选择">
            <el-option label="课程ID" value="1"></el-option>
            <el-option label="课程名字" value="2"></el-option>
          </el-select>
          <el-button @click="searchInput" slot="append" icon="el-icon-search"></el-button>
        </el-input>
        <div class="tabRight_content">
          <div class="classRow">
            <div class="class" v-for="item in searchList" @click="chooseClass(item)">
              <img :src="require(`../../../../../src/${item.image}`)" alt />
              <p :title="item.curName" class="classTitle">{{item.curName}}</p>
              <p class="classTeacher">
                {{item.curTeacher}}
              </p>
              <span class="evaluate">难度:</span>
              <el-rate
                v-model="item.evaluate"
                disabled
                show-score
                text-color="#ff9900"
                :colors="['#99A9BF', '#F5FF40', '#F5FF40']"
                :score-template="item.evaluate"
              ></el-rate>
            </div>
          </div>
        </div>
      </div>
      <ClassSelect
        v-else
        :curriculums="curriculums"
        :tab="tabNum"
        :currentPage="currentPage"
        :total="total"
      ></ClassSelect>
    </div>
  </div>
</template>

<script>
import ClassSelect from "../../components/classSelect/ClassSelect.vue";
import Head from "../../components/head/Head.vue";
import moment from "moment";
import { myAxios } from "../../request/index.js";

export default {
  data() {
    return {
      headTitle: "选课中心",
      tabNum: this.$store.state.tabNum,
      input: "",
      currentPage: 1,
      total: 0,
      curriculums: this.$store.state.curriculums,
      selectInput: "",
      selectTab: "",
      search: 0,
      searchList: "",
      name: "../../assets/search.png"
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
    if (this.tabNum == 0) {
      var res = await myAxios(this, {
        type: "curriculum",
        tab: 0,
        user: JSON.parse(sessionStorage.getItem("user"))
      });
    } else {
      var timeNow = moment().format("YYYY-MM-DD-HH:mm:ss");
      var res = await myAxios(this, {
        type: "curriculum",
        tab: this.tabNum,
        user: JSON.parse(sessionStorage.getItem("user")),
        timeNow
      });
    }
    this.curriculums = res.data;
    this.total = res.dataAll.length;
  },
  methods: {
    async chooseTab(tab) {
      this.tabNum = tab;
      this.$store.state.tabNum = tab;
      if (tab != 3) {
        this.currentPage = 1;
        this.selectInput = "";
        this.selectTab = "";
        this.searchList = "";
        var timeNow = moment().format("YYYY-MM-DD-HH:mm:ss");
        const res = await myAxios(this, {
          type: "curriculum",
          tab,
          user: JSON.parse(sessionStorage.getItem("user")),
          timeNow
        });
        this.curriculums = res.data;
        this.$store.state.curriculums = res.data;
        if(res == ''){
          this.total = 0
          return;
        }
        this.total = res.dataAll.length;
      }
    },
    async searchInput() {
      if (this.selectTab == "") {
        this.$message.error("你还未选择要查询的对象");
        return;
      }
      if (this.selectInput != "") {
        const res = await myAxios(this, {
          type: "searchCurInput",
          selectTab: this.selectTab,
          selectInput: this.selectInput
        });
        if (res == "") {
          this.$message({
            message: "查询结果为空",
            type: "warning"
          });
        }
        this.searchList = res;
      } else {
        this.$message.error("你还未输入内容");
      }
    },
    chooseClass(item) {
      sessionStorage.setItem("class", JSON.stringify(item));
      var res = JSON.parse(sessionStorage.getItem("class"));
      this.$router.push("classDetail");
    }
  },
  components: {
    ClassSelect,
    Head
  }
};
</script>

<style lang='less'>
.indexStu {
  .stuContent {
    width: 1400px;
    background-color: #fff;
    margin: 30px auto;
    display: flex;
    .tabLeft {
      ul {
        height: 100%;
        width: 235px;
        background-color: #f2f6f7;
        li {
          position: relative;
          width: 210px;
          padding-left: 25px;
          line-height: 85px;
          font-size: 21px;
          color: #b3bdc9;
          text-align: center;
          cursor: pointer;
        }
        .pseudo::after {
          content: "";
          position: absolute;
          top: 0;
          left: 0;
          width: 8px;
          height: 85px;
          background-color: #0087cf;
        }
        .allCurriculum {
          background: url("../../assets/allCurriculum.png") 50px center no-repeat;
          background-size: 26px 28px;
        }
   
        .allCurriculum1 {
          color: #0087cf !important;
          background: #fff url("../../assets/allCurriculum1.png") 50px center
            no-repeat;
          background-size: 26px 28px;
        }

        .curriculum {
          background: url("../../assets/curriculum.png") 50px center no-repeat;
          background-size: 25px 30px;
        }

        .curriculum1 {
          color: #0087cf !important;
          background: #fff url("../../assets/curriculum1.png") 50px center
            no-repeat;
          background-size: 25px 30px;
        }

        .unCurriculum {
          background: url("../../assets/unCurriculum.png") 47px center no-repeat;
          background-size: 27px 27px;
        }

        .unCurriculum1 {
          color: #0087cf !important;
          background: #fff url("../../assets/unCurriculum1.png") 47px center
            no-repeat;
          background-size: 27px 27px;
        }
        .search {
          background: url("../../assets/search.png") 50px center no-repeat;
          background-size: 26px 28px;
        }

        .search1 {
          color: #0087cf !important;
          background: #fff url("../../assets/search1.png") 50px center no-repeat;
          background-size: 26px 28px;
        }
      }
    }
    .tabRightIndex {
      width: 100%;
      padding-left: 40px;
      h2 {
        font-size: 34px;
        font-weight: 400;
        color: #000;
        margin: 30px 0;
      }
      .el-input-group {
        width: 48%;
        margin: 30px 206px;
        .el-input-group__prepend {
          width: 70px;
        }
      }
      .classRow {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: space-around;
        flex-wrap: wrap;
        margin-bottom: 30px;
        .class {
          width: 292px;
          margin-top: 20px;
          height: 280px;
          border: 1px solid #ccc;
          img {
            width: 100%;
            height: 150px;
          }
          .classTitle {
            color: #5d6269;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            font-size: 26px;
            font-weight: 600;
            margin: 10px 0 0 10px;
          }
          .classTeacher {
            color: #606266;
            font-size: 20px;
            font-weight: 600;
            margin: 10px 0 0 10px;
          }
          .paid {
            float: right;
            color: #ff5722;
            font-size: 20px;
            font-weight: 400;
            margin-right: 10px;
          }
          .evaluate {
            float: left;
            margin: 10px 5px 0 10px;
          }
          .el-rate {
            margin-top: 10px;
            .el-rate__icon {
              font-size: 22px;
            }
            .el-rate__text {
              font-size: 19px;
            }
          }
        }
        .class:hover {
          cursor: pointer;
          box-shadow: 10px 10px 5px #ccc;
        }
      }
    }
  }
}
</style>
