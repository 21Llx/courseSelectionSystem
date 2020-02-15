<template>
  <div class="tabRight">
    <div class="tabRight_content">
      <div class="classRow">
        <div class="class" v-for="item in curriculums" @click="chooseClass(item)">
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
      <div class="block">
        <el-pagination
          @current-change="handleCurrentChange"
          :current-page.sync="$parent.currentPage"
          :page-size="pageSize"
          layout="prev, pager, next, jumper"
          :total="total"
        ></el-pagination>
      </div>
    </div>
  </div>
</template>

<script>
import { myAxios } from "../../request/index.js";
import moment from "moment";

export default {
  props:['curriculums','tab','currentPage','total'],
  data() {
    return {
      input: "",
      pageSize: 6,
      value: 2.2,
      imgSrc: "./static/ss.jpg"
    };
  },
  methods: {
    async handleCurrentChange(val) {
      var timeNow = moment().format("YYYY-MM-DD-HH:mm:ss");
      var page = val;
      const res = await myAxios(this, { type: "curriculumChoose", page,tab:this.tab,user:JSON.parse(sessionStorage.getItem("user")),timeNow});
      this.$parent.curriculums = res;
    },
    chooseClass(item){
      sessionStorage.setItem('class',JSON.stringify(item))
      var res = JSON.parse(sessionStorage.getItem('class'))
      this.$router.push('classDetail')
    }
  }
};
</script>

<style lang='less'>
.tabRight {
  width: 100%;
  .tabRight_search {
    width: 600px;
    height: 80px;
    margin: 20px auto;
    .el-input {
      width: 400px;
      height: 60px;
      margin-right: 10px;
      font-size: 20px;
      .el-input__suffix {
        top: -8px;
      }
    }
    button {
      width: 120px;
      height: 56px;
      font-size: 20px;
    }
  }
  .tabRight_content {
    width: 100%;
    // height: 700px;
    .classRow {
      width: 100%;
      display: flex;
      align-items: center;
      justify-content: space-around;
      flex-wrap: wrap;
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
          text-overflow:ellipsis;
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
      .class:hover{
        cursor: pointer;
        box-shadow: 10px 10px 5px #ccc;
      }
    }
    .block {
      margin: 100px 34%;
      .el-pagination {
        font-weight: 400;
        ul {
          li {
            font-size: 20px;
          }
        }
        button {
          .el-icon {
            font-size: 20px;
          }
        }
        .el-pagination__jump {
          font-size: 22px;
          .el-input {
            font-size: 18px;
          }
        }
      }
    }
  }
}
</style>
