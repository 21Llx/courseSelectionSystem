<template>
  <div class="head">
    <img class="logo" src="../../assets/logo.png" alt />
    <p class="head_title">{{this.$parent.headTitle}}</p>
    <div class="head_per">
      <p>
        <span @click="$router.push('teacher')">{{teacher.name}}</span> |
        <span @click="Cancellation">注销</span>
      </p>
    </div>
  </div>
</template>

<script>
import { myAxios } from "../../request/index.js";

export default {
  data() {
    return {
      teacher: {}
    };
  },
  async created() {
    const res = await myAxios(this, {
      type: "getTeacher",
      teacherID: JSON.parse(sessionStorage.getItem("teacher"))
    });
    this.teacher = res;
  },
  methods: {
    Cancellation() {
      this.$confirm("是否要退出登陆", "提示", {
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        type: "warning"
      })
        .then(() => {
          this.$router.push("/");
          sessionStorage.removeItem("teacher");
        })
        .catch(() => {});
    }
  }
};
</script>

<style lang='less'>
.head {
  width: 100%;
  min-width: 1400px;
  height: 100px;
  background-color: #fff;
  .logo {
    margin-left: 150px;
    margin-top: 5px;
    float: left;
    cursor: pointer;
    height: 85px;
  }
  .head_title {
    float: left;
    line-height: 100px;
    font-size: 26px;
    font-weight: 400;
    margin-left: 27px;
  }
  .head_per {
    float: right;
    margin-right: 150px;
    line-height: 100px;
    font-size: 24px;
    p {
      cursor: default;
      span {
        cursor: pointer;
        font-size: 20px;
        color: #666;
      }
      span:hover {
        color: rgb(240, 11, 11);
      }
    }
  }
}
</style>
